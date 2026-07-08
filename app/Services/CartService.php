<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Sepet yapısı:
     * [ key => ['product_id' => int, 'quantity' => int, 'length_cm' => ?float, 'overlock' => bool] ]
     * key: standart üründe "p{id}", kesme halıda "c{id}_{boy}_{overlok}"
     */
    public function raw(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public static function makeKey(Product $product, ?float $lengthCm = null, bool $overlock = false): string
    {
        if ($product->is_cut && $lengthCm) {
            return 'c'.$product->id.'_'.rtrim(rtrim(number_format($lengthCm, 1, '.', ''), '0'), '.').'_'.(int) $overlock;
        }

        return 'p'.$product->id;
    }

    public function add(Product $product, int $quantity = 1, ?float $lengthCm = null, bool $overlock = false): void
    {
        $cart = $this->raw();
        $key = self::makeKey($product, $lengthCm, $overlock);
        $current = $cart[$key]['quantity'] ?? 0;

        $cart[$key] = [
            'product_id' => $product->id,
            'quantity' => min($current + $quantity, max(1, $product->stock)),
            'length_cm' => $product->is_cut ? $lengthCm : null,
            'overlock' => $product->is_cut && $overlock,
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(string $key, int $quantity): void
    {
        $cart = $this->raw();

        if (! isset($cart[$key])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $product = Product::find($cart[$key]['product_id']);
            $cart[$key]['quantity'] = $product ? min($quantity, max(1, $product->stock)) : $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(string $key): void
    {
        $cart = $this->raw();
        unset($cart[$key]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum(array_column($this->raw(), 'quantity'));
    }

    /**
     * Sepet satırları: key, product, quantity, length_cm, overlock, m2, unit_price, line_total
     */
    public function items(): array
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return [];
        }

        $products = Product::active()
            ->whereIn('id', array_unique(array_column($cart, 'product_id')))
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $key => $row) {
            $product = $products->get($row['product_id']);

            if (! $product) {
                continue;
            }

            $lengthCm = $row['length_cm'] ?? null;
            $overlock = (bool) ($row['overlock'] ?? false);

            if ($product->is_cut && $lengthCm) {
                $unitPrice = $product->cutPrice((float) $lengthCm, $overlock);
                $m2 = $product->cutM2((float) $lengthCm);
            } else {
                $unitPrice = $product->sale_price;
                $m2 = $product->m2 ? (float) $product->m2 : null;
            }

            $items[] = [
                'key' => $key,
                'product' => $product,
                'quantity' => $row['quantity'],
                'length_cm' => $lengthCm,
                'overlock' => $overlock,
                'm2' => $m2,
                'unit_price' => $unitPrice,
                'line_total' => round($unitPrice * $row['quantity'], 2),
            ];
        }

        return $items;
    }

    public function subtotal(): float
    {
        return round(array_sum(array_column($this->items(), 'line_total')), 2);
    }

    public function shippingCost(): float
    {
        $subtotal = $this->subtotal();

        if ($subtotal <= 0) {
            return 0;
        }

        $freeLimit = (float) Setting::get('free_shipping_limit', 0);

        if ($freeLimit > 0 && $subtotal >= $freeLimit) {
            return 0;
        }

        return (float) Setting::get('shipping_cost', 0);
    }

    public function total(): float
    {
        return round($this->subtotal() + $this->shippingCost(), 2);
    }
}
