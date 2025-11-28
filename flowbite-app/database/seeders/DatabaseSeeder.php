<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@unibo.it',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cfu_balance' => 1000.0000,
            'email_verified_at' => now(),
        ]);

        // Create test trader user
        User::factory()->create([
            'name' => 'Test Trader',
            'email' => 'trader@studio.unibo.it',
            'password' => Hash::make('password'),
            'role' => 'trader',
            'cfu_balance' => 100.0000,
            'email_verified_at' => now(),
        ]);

        // Seed global settings
        GlobalSetting::create([
            'key' => 'listing_fee',
            'value' => '20',
        ]);

        GlobalSetting::create([
            'key' => 'tax_rate',
            'value' => '0.02',
        ]);

        GlobalSetting::create([
            'key' => 'registration_bonus',
            'value' => '100',
        ]);
    }
}
