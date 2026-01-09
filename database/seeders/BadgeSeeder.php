<?php

namespace Database\Seeders;

use App\Models\Gamification\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Trade',
                'description' => 'Completa la tua prima transazione',
                'icon_path' => 'badges/first-trade.png',
            ],
            [
                'name' => 'Diamond Hands',
                'description' => 'Mantieni un meme per più di 30 giorni',
                'icon_path' => 'badges/diamond-hands.png',
            ],
            [
                'name' => 'Paper Hands',
                'description' => 'Vendi in perdita del 50% o più',
                'icon_path' => 'badges/paper-hands.png',
            ],
            [
                'name' => 'Whale',
                'description' => 'Possiedi più di 10,000 CFU',
                'icon_path' => 'badges/whale.png',
            ],
            [
                'name' => 'Day Trader',
                'description' => 'Completa 10 trade in un giorno',
                'icon_path' => 'badges/day-trader.png',
            ],
            [
                'name' => 'Meme Creator',
                'description' => 'Crea e fai approvare un meme',
                'icon_path' => 'badges/meme-creator.png',
            ],
            [
                'name' => 'Diversified Portfolio',
                'description' => 'Possiedi almeno 10 meme diversi',
                'icon_path' => 'badges/diversified.png',
            ],
            [
                'name' => 'Stonks Master',
                'description' => 'Guadagna 500% su un singolo meme',
                'icon_path' => 'badges/stonks-master.png',
            ],
            [
                'name' => 'Early Adopter',
                'description' => 'Compra un meme nelle prime 24h dalla quotazione',
                'icon_path' => 'badges/early-adopter.png',
            ],
            [
                'name' => 'Lucky Trader',
                'description' => 'Vendi al prezzo massimo storico',
                'icon_path' => 'badges/lucky-trader.png',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
