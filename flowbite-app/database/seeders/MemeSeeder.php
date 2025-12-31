<?php

namespace Database\Seeders;

use App\Models\Meme;
use App\Models\PriceHistory;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * I meme sono salvati in storage/memes/{user_id}/{filename}
     * Tu dovrai creare le cartelle e inserire le immagini manualmente.
     * 
     * Struttura attesa:
     * - storage/memes/2/meme1.jpg  (Marco - utente id 2)
     * - storage/memes/3/meme2.jpg  (Giulia - utente id 3)
     * - storage/memes/4/meme3.jpg  (Alessandro - utente id 4)
     * - storage/memes/2/meme4.jpg  (Marco - utente id 2)
     * - storage/memes/5/meme5.jpg  (Sofia - utente id 5)
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $traders = User::where('role', 'trader')->get();
        $categories = Category::all();

        // Meme 1: Classico - Creato da Marco (id: 2)
        $meme1 = Meme::create([
            'creator_id' => 2, // Marco
            'category_id' => $categories->where('slug', 'classici')->first()->id,
            'title' => 'Stonks Only Go Up',
            'ticker' => '$STONK',
            'image_path' => '2/meme1.jpg',
            'base_price' => 1.00000,
            'slope' => 0.10000,
            'current_price' => 3.50000, // Con 25 azioni = 1 + (0.1 * 25) = 3.5
            'circulating_supply' => 25,
            'status' => 'approved',
            'approved_at' => now()->subDays(15),
            'approved_by' => $admin->id,
            'trading_starts_at' => now()->subDays(14),
        ]);

        // Crea storico prezzi per meme1
        $this->createPriceHistory($meme1, 15);

        // Meme 2: Università - Creato da Giulia (id: 3)
        $meme2 = Meme::create([
            'creator_id' => 3, // Giulia
            'category_id' => $categories->where('slug', 'universita')->first()->id,
            'title' => 'Esame alle 8 di mattina',
            'ticker' => '$ESAME',
            'image_path' => '3/meme2.jpg',
            'base_price' => 0.50000,
            'slope' => 0.05000,
            'current_price' => 1.50000, // Con 20 azioni = 0.5 + (0.05 * 20) = 1.5
            'circulating_supply' => 20,
            'status' => 'approved',
            'approved_at' => now()->subDays(10),
            'approved_by' => $admin->id,
            'trading_starts_at' => now()->subDays(9),
        ]);

        $this->createPriceHistory($meme2, 10);

        // Meme 3: Gaming - Creato da Alessandro (id: 4) - Alta volatilità
        $meme3 = Meme::create([
            'creator_id' => 4, // Alessandro
            'category_id' => $categories->where('slug', 'gaming')->first()->id,
            'title' => 'Press F to Pay Respects',
            'ticker' => '$PRESSF',
            'image_path' => '4/meme3.jpg',
            'base_price' => 2.00000,
            'slope' => 0.50000, // Alta volatilità
            'current_price' => 7.00000, // Con 10 azioni = 2 + (0.5 * 10) = 7
            'circulating_supply' => 10,
            'status' => 'approved',
            'approved_at' => now()->subDays(5),
            'approved_by' => $admin->id,
            'trading_starts_at' => now()->subDays(4),
        ]);

        $this->createPriceHistory($meme3, 5);

        // Meme 4: Crypto - Creato da Marco (id: 2) - Low volatility
        $meme4 = Meme::create([
            'creator_id' => 2, // Marco
            'category_id' => $categories->where('slug', 'crypto')->first()->id,
            'title' => 'HODL Forever',
            'ticker' => '$HODL',
            'image_path' => '2/meme4.jpg',
            'base_price' => 1.50000,
            'slope' => 0.02000, // Bassa volatilità - Blue chip
            'current_price' => 2.10000, // Con 30 azioni = 1.5 + (0.02 * 30) = 2.1
            'circulating_supply' => 30,
            'status' => 'approved',
            'approved_at' => now()->subDays(20),
            'approved_by' => $admin->id,
            'trading_starts_at' => now()->subDays(19),
        ]);

        $this->createPriceHistory($meme4, 20);

        // Meme 5: Attualità - Creato da Sofia (id: 5) - Pending (non ancora approvato)
        $meme5 = Meme::create([
            'creator_id' => 5, // Sofia
            'category_id' => $categories->where('slug', 'attualita')->first()->id,
            'title' => 'Quando il prof dice "ultima domanda"',
            'ticker' => '$ULTIMA',
            'image_path' => '5/meme5.jpg',
            'base_price' => 1.00000,
            'slope' => 0.15000,
            'current_price' => 1.00000,
            'circulating_supply' => 0,
            'status' => 'pending', // In attesa di approvazione
            'approved_at' => null,
            'approved_by' => null,
            'trading_starts_at' => null,
        ]);

        // Il meme pending non ha storico prezzi ancora
    }

    /**
     * Crea uno storico prezzi fittizio per un meme
     */
    private function createPriceHistory(Meme $meme, int $days): void
    {
        $basePrice = (float) $meme->base_price;
        $slope = (float) $meme->slope;
        
        // Inizia con IPO
        PriceHistory::create([
            'meme_id' => $meme->id,
            'price' => $basePrice,
            'circulating_supply_snapshot' => 0,
            'trigger_type' => 'ipo',
            'recorded_at' => now()->subDays($days),
            'volume_24h' => 0,
            'pct_change_24h' => 0,
        ]);

        // Simula alcune transazioni nel tempo
        $supply = 0;
        $previousPrice = $basePrice;
        
        for ($i = $days - 1; $i >= 0; $i--) {
            // Simula 1-3 transazioni al giorno
            $transactions = rand(1, 3);
            
            for ($j = 0; $j < $transactions; $j++) {
                // Compra o vendi random
                $isBuy = rand(0, 1) === 1 || $supply < 5;
                
                if ($isBuy) {
                    $qty = rand(1, 5);
                    $supply += $qty;
                } else {
                    if ($supply > 0) {
                        $qty = rand(1, min(3, $supply));
                        $supply -= $qty;
                    } else {
                        continue;
                    }
                }

                $price = $basePrice + ($slope * $supply);
                $pctChange = $previousPrice > 0 ? (($price - $previousPrice) / $previousPrice) * 100 : 0;

                PriceHistory::create([
                    'meme_id' => $meme->id,
                    'price' => $price,
                    'circulating_supply_snapshot' => $supply,
                    'trigger_type' => $isBuy ? 'buy' : 'sell',
                    'recorded_at' => now()->subDays($i)->addHours(rand(8, 22)),
                    'volume_24h' => rand(10, 100),
                    'pct_change_24h' => round($pctChange, 4),
                ]);

                $previousPrice = $price;
            }
        }
    }
}
