<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_code',
        'm2', 'length_cm', 'overlock', 'unit_price', 'quantity', 'line_total',
    ];

    protected $casts = [
        'm2' => 'decimal:2',
        'length_cm' => 'decimal:2',
        'overlock' => 'boolean',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
