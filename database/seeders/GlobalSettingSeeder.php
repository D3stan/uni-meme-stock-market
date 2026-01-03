<?php

namespace Database\Seeders;

use App\Models\Admin\GlobalSetting;
use Illuminate\Database\Seeder;

class GlobalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'listing_fee', 'value' => '20.00'],
            ['key' => 'tax_rate', 'value' => '0.02'], // 2% fee on trades
            ['key' => 'daily_bonus', 'value' => '10.00'],
            ['key' => 'registration_bonus', 'value' => '100.00'],
            ['key' => 'min_trade_amount', 'value' => '1'],
            ['key' => 'max_trade_amount', 'value' => '1000'],
            ['key' => 'dividend_frequency_days', 'value' => '7'],
        ];

        foreach ($settings as $setting) {
            GlobalSetting::create($setting);
        }
    }
}
