<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    public function index()
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'shipping' => $this->cart->shippingCost(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('error', 'Bu ürün stokta yok.');
        }

        $lengthCm = null;
        $overlock = false;

        if ($product->is_cut) {
            $request->validate([
                'length_cm' => "required|numeric|min:{$product->cut_min_cm}|max:{$product->cut_max_cm}",
            ], [], ['length_cm' => 'Boy (cm)']);

            $lengthCm = (float) $request->input('length_cm');
            $overlock = $request->boolean('overlock');
        }

        $this->cart->add($product, max(1, (int) $request->input('quantity', 1)), $lengthCm, $overlock);

        return redirect()->route('cart.index')->with('success', 'Ürün sepete eklendi.');
    }

    public function update(Request $request, string $key)
    {
        $this->cart->update($key, (int) $request->input('quantity', 1));

        return redirect()->route('cart.index');
    }

    public function remove(string $key)
    {
        $this->cart->remove($key);

        return redirect()->route('cart.index')->with('success', 'Ürün sepetten çıkarıldı.');
    }
}
