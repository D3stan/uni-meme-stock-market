<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GlobalSetting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Cache duration in seconds
     */
    protected static int $cacheDuration = 3600; // 1 ora

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("global_setting_{$key}", static::$cacheDuration, function () use ($key, $default) {
            $setting = static::find($key);
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );
        
        Cache::forget("global_setting_{$key}");
    }

    /**
     * Get setting as float
     */
    public static function getFloat(string $key, float $default = 0.0): float
    {
        return (float) static::get($key, $default);
    }

    /**
     * Get setting as integer
     */
    public static function getInt(string $key, int $default = 0): int
    {
        return (int) static::get($key, $default);
    }

    /**
     * Get setting as boolean
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        $value = static::get($key, $default);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get listing fee
     */
    public static function getListingFee(): float
    {
        return static::getFloat('listing_fee', 20.0);
    }

    /**
     * Get tax rate
     */
    public static function getTaxRate(): float
    {
        return static::getFloat('tax_rate', 0.02);
    }

    /**
     * Get registration bonus
     */
    public static function getRegistrationBonus(): float
    {
        return static::getFloat('registration_bonus', 100.0);
    }

    /**
     * Get slippage threshold
     */
    public static function getSlippageThreshold(): float
    {
        return static::getFloat('slippage_threshold', 0.02);
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("global_setting_{$setting->key}");
        }
    }
}
