<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'code', 'category_id', 'size_text', 'm2',
        'price_per_m2', 'total_price', 'discount_price', 'description',
        'features', 'cover_image', 'stock', 'is_active', 'is_featured',
        'is_campaign', 'is_cut', 'cut_width_cm', 'cut_min_cm', 'cut_max_cm', 'views',
    ];

    protected $casts = [
        'features' => 'array',
        'm2' => 'decimal:2',
        'price_per_m2' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'cut_width_cm' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_campaign' => 'boolean',
        'is_cut' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /** Satış fiyatı: indirimli fiyat varsa o, yoksa toplam fiyat (kesme halıda m² fiyatı) */
    public function getSalePriceAttribute(): float
    {
        return (float) ($this->discount_price ?? $this->total_price ?? $this->price_per_m2 ?? 0);
    }

    public function getHasDiscountAttribute(): bool
    {
        return ! $this->is_cut
            && $this->discount_price !== null
            && $this->total_price !== null
            && (float) $this->discount_price > 0
            && (float) $this->discount_price < (float) $this->total_price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->has_discount) {
            return 0;
        }

        return (int) round((1 - $this->discount_price / $this->total_price) * 100);
    }

    /** Kesme halı: verilen boy (cm) için m² hesabı */
    public function cutM2(float $lengthCm): float
    {
        return round(((float) $this->cut_width_cm / 100) * ($lengthCm / 100), 2);
    }

    /** Kesme halı: verilen boy ve overlok seçimi için birim fiyat */
    public function cutPrice(float $lengthCm, bool $overlock = false): float
    {
        $price = round($this->cutM2($lengthCm) * (float) $this->price_per_m2, 2);

        if ($overlock) {
            $price += (float) Setting::get('overlock_price', 0);
        }

        return round($price, 2);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
