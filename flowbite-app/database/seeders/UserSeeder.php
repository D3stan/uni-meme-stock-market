<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin principale
        User::create([
            'name' => 'Rettore Admin',
            'nickname' => 'IlRettore',
            'email' => 'admin@unibo.it',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cfu_balance' => 10000.00000,
            'is_suspended' => false,
            'status' => 'Il Rettorato osserva tutto 👀',
            'email_verified_at' => now(),
            'cached_net_worth' => 10000.00000,
        ]);

        // Traders di test
        $traders = [
            [
                'name' => 'Marco Rossi',
                'nickname' => 'TraderMarco',
                'email' => 'marco.rossi@studio.unibo.it',
                'cfu_balance' => 250.00000,
                'status' => 'Diamond hands only 💎🙌',
            ],
            [
                'name' => 'Giulia Bianchi',
                'nickname' => 'CryptoGiulia',
                'email' => 'giulia.bianchi@studio.unibo.it',
                'cfu_balance' => 180.00000,
                'status' => 'To the moon! 🚀',
            ],
            [
                'name' => 'Alessandro Verdi',
                'nickname' => 'AlexTheWhale',
                'email' => 'alessandro.verdi@studio.unibo.it',
                'cfu_balance' => 520.00000,
                'status' => 'Buy the dip 📉📈',
            ],
            [
                'name' => 'Sofia Romano',
                'nickname' => 'SofiaInvest',
                'email' => 'sofia.romano@studio.unibo.it',
                'cfu_balance' => 95.00000,
                'status' => null,
            ],
            [
                'name' => 'Luca Ferrari',
                'nickname' => null,
                'email' => 'luca.ferrari@studio.unibo.it',
                'cfu_balance' => 100.00000,
                'status' => 'Nuovo qui, ancora studio il mercato',
            ],
        ];

        foreach ($traders as $trader) {
            User::create([
                'name' => $trader['name'],
                'nickname' => $trader['nickname'],
                'email' => $trader['email'],
                'password' => Hash::make('password'),
                'role' => 'trader',
                'cfu_balance' => $trader['cfu_balance'],
                'is_suspended' => false,
                'status' => $trader['status'],
                'email_verified_at' => now(),
                'cached_net_worth' => $trader['cfu_balance'],
            ]);
        }
    }
}
