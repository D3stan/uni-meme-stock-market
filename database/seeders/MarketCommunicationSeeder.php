<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin\MarketCommunication;
use Illuminate\Database\Seeder;

class MarketCommunicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $communications = [
            [
                'message' => '🎉 Benvenuti su AlmaStreet! Il mercato è ufficialmente aperto. Buon trading a tutti!',
                'is_active' => false,
                'expires_at' => now()->subDays(10),
                'created_at' => now()->subDays(15),
            ],
            [
                'message' => '⚠️ Attenzione: alta volatilità registrata su $DRAKE e $STONK. Fare trading con cautela.',
                'is_active' => false,
                'expires_at' => now()->subDays(2),
                'created_at' => now()->subDays(5),
            ],
            [
                'message' => '📢 Il Rettorato comunica: il mercato rimarrà aperto durante le vacanze. Gli uffici amministrativi saranno chiusi dal 24/12 al 06/01.',
                'is_active' => true,
                'expires_at' => now()->addDays(7),
                'created_at' => now()->subDays(3),
            ],
            [
                'message' => '🎊 Nuovo anno, nuovi meme! Aspettatevi molte nuove quotazioni nelle prossime settimane.',
                'is_active' => true,
                'expires_at' => now()->addDays(14),
                'created_at' => now()->subDay(),
            ],
        ];

        foreach ($communications as $comm) {
            MarketCommunication::create([
                'admin_id' => $admin->id,
                'message' => $comm['message'],
                'is_active' => $comm['is_active'],
                'expires_at' => $comm['expires_at'],
                'created_at' => $comm['created_at'],
            ]);
        }
    }
}
