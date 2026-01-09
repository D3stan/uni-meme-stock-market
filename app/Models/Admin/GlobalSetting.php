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

    /**
     * Retrieve a global setting value by key, or return a default if not found.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::find($key);

        return $setting ? $setting->value : $default;
    }

    /**
     * Store or update a global setting with the given key-value pair.
     *
     * @param  mixed  $value
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Check if a global setting with the given key exists.
     */
    public static function has(string $key): bool
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Delete a global setting by key, returning true if successful.
     */
    public static function forget(string $key): bool
    {
        return static::where('key', $key)->delete() > 0;
    }

    /**
     * Retrieve the CFU fee required to list a new meme on the market.
     */
    public static function listingFee(): float
    {
        return (float) static::get('listing_fee', 20.0);
    }

    /**
     * Retrieve the percentage tax rate applied to transactions.
     */
    public static function taxRate(): float
    {
        return (float) static::get('tax_rate', 0.02);
    }

    /**
     * Retrieve the daily CFU bonus amount awarded to active users.
     */
    public static function dailyBonus(): float
    {
        return (float) static::get('daily_bonus', 10.0);
    }

    /**
     * Retrieve the starting CFU balance given to new users upon registration.
     */
    public static function initialBalance(): float
    {
        return (float) static::get('initial_balance', 100.0);
    }
}
