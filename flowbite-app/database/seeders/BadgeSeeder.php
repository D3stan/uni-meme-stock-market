<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Diamond Hands',
                'slug' => 'diamond-hands',
                'description' => 'Mantiene un titolo in portafoglio per più di 1 settimana senza venderlo.',
                'icon_path' => null,
            ],
            [
                'name' => 'Paper Hands',
                'slug' => 'paper-hands',
                'description' => 'Vende un titolo entro 1 ora dall\'acquisto.',
                'icon_path' => null,
            ],
            [
                'name' => 'Early Adopter',
                'slug' => 'early-adopter',
                'description' => 'Acquista tra i primi 10 un meme appena quotato.',
                'icon_path' => null,
            ],
            [
                'name' => 'Liquidator',
                'slug' => 'liquidator',
                'description' => 'Raggiunge 0 CFU di saldo (Badge "Bancarotta").',
                'icon_path' => null,
            ],
            [
                'name' => 'Whale',
                'slug' => 'whale',
                'description' => 'Possiede più di 1000 CFU in un singolo titolo.',
                'icon_path' => null,
            ],
            [
                'name' => 'Diversificatore',
                'slug' => 'diversificatore',
                'description' => 'Possiede almeno 5 titoli diversi nel portafoglio.',
                'icon_path' => null,
            ],
            [
                'name' => 'Top Trader',
                'slug' => 'top-trader',
                'description' => 'Raggiunge la top 10 della leaderboard.',
                'icon_path' => null,
            ],
            [
                'name' => 'Meme Creator',
                'slug' => 'meme-creator',
                'description' => 'Ha creato e fatto approvare almeno un meme.',
                'icon_path' => null,
            ],
            [
                'name' => 'Dividend Hunter',
                'slug' => 'dividend-hunter',
                'description' => 'Ha ricevuto dividendi per un totale di 100+ CFU.',
                'icon_path' => null,
            ],
            [
                'name' => 'Newbie',
                'slug' => 'newbie',
                'description' => 'Badge di benvenuto per i nuovi utenti.',
                'icon_path' => null,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
