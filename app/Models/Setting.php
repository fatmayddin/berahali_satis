<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * WhatsApp numarasını wa.me formatına normalize eder (905xxxxxxxxx).
     * "0 555 123 45 67", "+90 555...", "5551234567" gibi girişlerin hepsini düzeltir.
     */
    public static function whatsappNumber(): ?string
    {
        $digits = preg_replace('/\D/', '', (string) static::get('whatsapp', ''));

        if ($digits === '' || strlen($digits) < 10) {
            return null;
        }

        if (str_starts_with($digits, '90') && strlen($digits) === 12) {
            return $digits;
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return '9'.$digits; // 05xx -> 905xx
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '5')) {
            return '90'.$digits;
        }

        return $digits;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
        static::deleted(fn () => Cache::forget('site_settings'));
    }
}
