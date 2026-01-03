<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    // Primary key is 'key' instead of 'id'
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];

    // Static facade pattern for easy access
    public static function get(string $key, $default = null)
    {
        $setting = static::find($key);
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function has(string $key): bool
    {
        return static::where('key', $key)->exists();
    }

    public static function forget(string $key): bool
    {
        return static::where('key', $key)->delete() > 0;
    }

    // Helper methods for common settings
    public static function listingFee(): float
    {
        return (float) static::get('listing_fee', 20.0);
    }

    public static function taxRate(): float
    {
        return (float) static::get('tax_rate', 0.02);
    }

    public static function dailyBonus(): float
    {
        return (float) static::get('daily_bonus', 10.0);
    }

    public static function initialBalance(): float
    {
        return (float) static::get('initial_balance', 100.0);
    }
}
