<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'name', 'email', 'phone', 'address',
        'city', 'district', 'note', 'shipping_method', 'subtotal',
        'shipping_cost', 'total', 'status', 'payment_id', 'conversation_id',
        'iyzico_token', 'payment_error', 'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public const SHIPPING_METHODS = [
        'cargo' => 'Kargo ile Gönderim',
        'same_day' => 'Aynı Gün Teslimat (Mağaza Aracı)',
    ];

    public const STATUSES = [
        'pending' => 'Ödeme Bekliyor',
        'paid' => 'Ödendi',
        'preparing' => 'Hazırlanıyor',
        'shipped' => 'Kargoya Verildi',
        'delivered' => 'Teslim Edildi',
        'cancelled' => 'İptal Edildi',
        'failed' => 'Ödeme Başarısız',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getShippingMethodLabelAttribute(): string
    {
        return self::SHIPPING_METHODS[$this->shipping_method] ?? $this->shipping_method;
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'BH'.now()->format('ymd').strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
