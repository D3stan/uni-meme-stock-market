<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Meme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Crea posizioni nel portafoglio e transazioni correlate
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();
        $memes = Meme::where('status', 'approved')->get();

        // Marco (id: 2) possiede azioni di $ESAME e $PRESSF
        $this->createPosition(
            userId: 2,
            memeId: $memes->where('ticker', '$ESAME')->first()->id,
            quantity: 8,
            avgBuyPrice: 0.85
        );

        $this->createPosition(
            userId: 2,
            memeId: $memes->where('ticker', '$PRESSF')->first()->id,
            quantity: 3,
            avgBuyPrice: 4.50
        );

        // Giulia (id: 3) possiede azioni di $STONK e $HODL
        $this->createPosition(
            userId: 3,
            memeId: $memes->where('ticker', '$STONK')->first()->id,
            quantity: 10,
            avgBuyPrice: 2.00
        );

        $this->createPosition(
            userId: 3,
            memeId: $memes->where('ticker', '$HODL')->first()->id,
            quantity: 15,
            avgBuyPrice: 1.80
        );

        // Alessandro (id: 4) - whale, possiede molte azioni di $STONK
        $this->createPosition(
            userId: 4,
            memeId: $memes->where('ticker', '$STONK')->first()->id,
            quantity: 12,
            avgBuyPrice: 1.50
        );

        $this->createPosition(
            userId: 4,
            memeId: $memes->where('ticker', '$HODL')->first()->id,
            quantity: 10,
            avgBuyPrice: 1.70
        );

        // Sofia (id: 5) - poche azioni, sta iniziando
        $this->createPosition(
            userId: 5,
            memeId: $memes->where('ticker', '$ESAME')->first()->id,
            quantity: 5,
            avgBuyPrice: 1.20
        );

        // Luca (id: 6) - nessuna posizione, è nuovo
    }

    /**
     * Crea una posizione nel portafoglio e la transazione correlata
     */
    private function createPosition(int $userId, int $memeId, int $quantity, float $avgBuyPrice): void
    {
        $user = User::find($userId);
        $meme = Meme::find($memeId);
        
        if (!$user || !$meme) return;

        // Crea la posizione
        Portfolio::create([
            'user_id' => $userId,
            'meme_id' => $memeId,
            'quantity' => $quantity,
            'avg_buy_price' => $avgBuyPrice,
        ]);

        // Crea la transazione di acquisto
        $totalCost = $quantity * $avgBuyPrice;
        $feeRate = 0.02; // 2%
        $fee = $totalCost * $feeRate;
        $totalWithFee = $totalCost + $fee;

        Transaction::create([
            'user_id' => $userId,
            'meme_id' => $memeId,
            'type' => 'buy',
            'quantity' => $quantity,
            'price_per_share' => $avgBuyPrice,
            'fee_amount' => $fee,
            'total_amount' => $totalWithFee,
            'cfu_balance_after' => $user->cfu_balance, // Già sottratto nel seeder utenti
            'executed_at' => now()->subDays(rand(1, 10)),
        ]);
    }
}
