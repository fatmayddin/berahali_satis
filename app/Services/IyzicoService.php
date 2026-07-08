<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * İyzico Ödeme Formu (Checkout Form) entegrasyonu.
 * SDK bağımlılığı olmadan, iyzico REST API v2 (HmacSHA256) imzalama ile çalışır.
 * Sandbox/canlı ortam .env üzerinden IYZICO_BASE_URL ile seçilir.
 */
class IyzicoService
{
    protected string $apiKey;
    protected string $secretKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.iyzico.api_key');
        $this->secretKey = config('services.iyzico.secret_key');
        $this->baseUrl = rtrim(config('services.iyzico.base_url'), '/');
    }

    /**
     * Ödeme formunu başlatır; başarılıysa checkoutFormContent (script) döner.
     */
    public function initializeCheckoutForm(Order $order, string $callbackUrl, string $buyerIp): array
    {
        $nameParts = explode(' ', trim($order->name), 2);

        $basketItems = $order->items->map(fn ($item) => [
            'id' => (string) ($item->product_id ?? $item->id),
            'name' => $item->product_name,
            'category1' => 'Halı',
            'itemType' => 'PHYSICAL',
            'price' => $this->money($item->line_total),
        ])->values()->all();

        $address = [
            'contactName' => $order->name,
            'city' => $order->city,
            'country' => 'Turkey',
            'address' => $order->address.($order->district ? ' '.$order->district : ''),
        ];

        $body = [
            'locale' => 'tr',
            'conversationId' => $order->conversation_id,
            'price' => $this->money($order->subtotal),
            'paidPrice' => $this->money($order->total),
            'currency' => 'TRY',
            'basketId' => $order->order_number,
            'paymentGroup' => 'PRODUCT',
            'callbackUrl' => $callbackUrl,
            'enabledInstallments' => [1, 2, 3, 6, 9],
            'buyer' => [
                'id' => (string) ($order->user_id ?? 'guest-'.$order->id),
                'name' => $nameParts[0],
                'surname' => $nameParts[1] ?? $nameParts[0],
                'gsmNumber' => $order->phone,
                'email' => $order->email,
                'identityNumber' => '11111111111',
                'registrationAddress' => $address['address'],
                'ip' => $buyerIp,
                'city' => $order->city,
                'country' => 'Turkey',
            ],
            'shippingAddress' => $address,
            'billingAddress' => $address,
            'basketItems' => $basketItems,
        ];

        return $this->request('/payment/iyzipos/checkoutform/initialize/auth/ecom', $body);
    }

    /**
     * Callback sonrası ödeme sonucunu token ile sorgular.
     */
    public function retrieveCheckoutForm(string $token, ?string $conversationId = null): array
    {
        return $this->request('/payment/iyzipos/checkoutform/auth/ecom/detail', [
            'locale' => 'tr',
            'conversationId' => $conversationId ?? (string) Str::uuid(),
            'token' => $token,
        ]);
    }

    protected function request(string $path, array $body): array
    {
        $jsonBody = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $randomKey = (string) (now()->getTimestampMs()).rand(100000, 999999);

        $signature = hash_hmac('sha256', $randomKey.$path.$jsonBody, $this->secretKey);
        $authorization = base64_encode(
            'apiKey:'.$this->apiKey.'&randomKey:'.$randomKey.'&signature:'.$signature
        );

        $response = Http::withHeaders([
            'Authorization' => 'IYZWSv2 '.$authorization,
            'x-iyzi-rnd' => $randomKey,
            'Content-Type' => 'application/json',
        ])->withBody($jsonBody, 'application/json')
            ->post($this->baseUrl.$path);

        return $response->json() ?? [
            'status' => 'failure',
            'errorMessage' => 'İyzico sunucusuna ulaşılamadı.',
        ];
    }

    protected function money($amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
