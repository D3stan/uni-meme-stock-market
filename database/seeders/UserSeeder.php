<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin AlmaStreet',
            'email' => 'admin@studio.unibo.it',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cfu_balance' => 10000.00,
            'is_suspended' => false,
            'avatar' => 'avatar.png'
        ]);

        // Regular traders
        $traders = [
            ['name' => 'Mario Rossi', 'email' => 'mario.rossi@studio.unibo.it', 'cfu_balance' => 1250.00, 'avatar' =>'profile.jpg'],
            ['name' => 'Laura Bianchi', 'email' => 'laura.bianchi@studio.unibo.it', 'cfu_balance' => 2100.00],
            ['name' => 'Giuseppe Verdi', 'email' => 'giuseppe.verdi@studio.unibo.it', 'cfu_balance' => 850.00, 'avatar' =>'front.png'],
            ['name' => 'Anna Ferrari', 'email' => 'anna.ferrari@studio.unibo.it', 'cfu_balance' => 3200.00],
            ['name' => 'Luca Conti', 'email' => 'luca.conti@studio.unibo.it', 'cfu_balance' => 1500.00],
        ];

        foreach ($traders as $trader) {
            User::create([
                'name' => $trader['name'],
                'email' => $trader['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'trader',
                'cfu_balance' => $trader['cfu_balance'],
                'is_suspended' => false,
                'avatar' => $trader['avatar'] ?? null
            ]);
        }
    }
}
