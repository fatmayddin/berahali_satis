<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\IyzicoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected IyzicoService $iyzico,
    ) {
    }

    public function index()
    {
        if (empty($this->cart->items())) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        return view('checkout.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'shippingCargo' => $this->cart->shippingCostFor('cargo'),
            'shippingSameDay' => $this->cart->shippingCostFor('same_day'),
        ]);
    }

    public function pay(Request $request)
    {
        $items = $this->cart->items();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:1000',
            'shipping_method' => 'required|in:cargo,same_day',
        ], [], [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'address' => 'Adres',
            'city' => 'İl',
            'district' => 'İlçe',
            'note' => 'Sipariş Notu',
            'shipping_method' => 'Teslimat Yöntemi',
        ]);

        // Stok kontrolü
        foreach ($items as $item) {
            if ($item['product']->stock < $item['quantity']) {
                return back()->with('error', $item['product']->name.' için yeterli stok yok.');
            }
        }

        $order = DB::transaction(function () use ($data, $items) {
            $order = Order::create([
                ...$data,
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'subtotal' => $this->cart->subtotal(),
                'shipping_cost' => $this->cart->shippingCostFor($data['shipping_method']),
                'total' => $this->cart->total($data['shipping_method']),
                'status' => 'pending',
                'conversation_id' => (string) Str::uuid(),
            ]);

            foreach ($items as $item) {
                $name = $item['product']->name;

                if ($item['product']->is_cut && $item['length_cm']) {
                    $name .= sprintf(
                        ' (%s cm en × %s cm boy%s)',
                        rtrim(rtrim(number_format((float) $item['product']->cut_width_cm, 1, ',', ''), '0'), ','),
                        rtrim(rtrim(number_format((float) $item['length_cm'], 1, ',', ''), '0'), ','),
                        $item['overlock'] ? ', overloklu' : ''
                    );
                }

                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $name,
                    'product_code' => $item['product']->code,
                    'm2' => $item['m2'],
                    'length_cm' => $item['length_cm'],
                    'overlock' => $item['overlock'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $result = $this->iyzico->initializeCheckoutForm(
            $order->load('items'),
            route('checkout.callback'),
            $request->ip()
        );

        if (($result['status'] ?? null) !== 'success') {
            Log::error('İyzico initialize hatası', ['order' => $order->order_number, 'result' => $result]);
            $order->update(['status' => 'failed', 'payment_error' => $result['errorMessage'] ?? 'Bilinmeyen hata']);

            return back()->with('error', 'Ödeme başlatılamadı: '.($result['errorMessage'] ?? 'Lütfen tekrar deneyin.'));
        }

        $order->update(['iyzico_token' => $result['token'] ?? null]);
        session(['pending_order_id' => $order->id]);

        return view('checkout.payment', [
            'order' => $order,
            'checkoutFormContent' => $result['checkoutFormContent'],
        ]);
    }

    public function callback(Request $request)
    {
        $token = $request->input('token');

        if (! $token) {
            return redirect()->route('checkout.failed');
        }

        $order = Order::where('iyzico_token', $token)->first()
            ?? Order::find(session('pending_order_id'));

        if (! $order) {
            return redirect()->route('checkout.failed');
        }

        $result = $this->iyzico->retrieveCheckoutForm($token, $order->conversation_id);

        if (($result['status'] ?? null) === 'success' && ($result['paymentStatus'] ?? null) === 'SUCCESS') {
            $order->update([
                'status' => 'paid',
                'payment_id' => $result['paymentId'] ?? null,
                'paid_at' => now(),
            ]);

            // Stok düş
            foreach ($order->items as $item) {
                $item->product?->decrement('stock', min($item->quantity, $item->product->stock));
            }

            $this->cart->clear();
            session()->forget('pending_order_id');
            session(['last_order_number' => $order->order_number]);

            // E-posta bildirimleri (hata olsa bile siparişi etkilemesin)
            try {
                \Illuminate\Support\Facades\Mail::to($order->email)
                    ->send(new \App\Mail\OrderPlacedCustomerMail($order));

                $adminEmail = \App\Models\Setting::get('email');

                if ($adminEmail) {
                    \Illuminate\Support\Facades\Mail::to($adminEmail)
                        ->send(new \App\Mail\OrderPlacedAdminMail($order));
                }
            } catch (\Throwable $e) {
                Log::warning('Sipariş maili gönderilemedi: '.$e->getMessage());
            }

            return redirect()->route('checkout.success');
        }

        Log::warning('İyzico ödeme başarısız', ['order' => $order->order_number, 'result' => $result]);
        $order->update([
            'status' => 'failed',
            'payment_error' => $result['errorMessage'] ?? 'Ödeme tamamlanamadı.',
        ]);

        return redirect()->route('checkout.failed')
            ->with('error', $result['errorMessage'] ?? 'Ödeme tamamlanamadı.');
    }

    public function success()
    {
        $orderNumber = session('last_order_number');

        if (! $orderNumber) {
            return redirect()->route('home');
        }

        return view('checkout.success', ['orderNumber' => $orderNumber]);
    }

    public function failed()
    {
        return view('checkout.failed');
    }
}
