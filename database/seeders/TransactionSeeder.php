<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Market\Meme;
use App\Models\Financial\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();
        $memes = Meme::where('status', 'approved')->get();
        $taxRate = 0.02; // 2% fee

        foreach ($traders as $trader) {
            // Registration bonus
            Transaction::create([
                'user_id' => $trader->id,
                'meme_id' => null,
                'type' => 'bonus',
                'quantity' => 0,
                'price_per_share' => 0,
                'fee_amount' => 0,
                'total_amount' => 100.00,
                'cfu_balance_after' => 100.00,
                'executed_at' => $trader->created_at,
            ]);

            // Generate 5-15 random transactions per trader
            $numTransactions = rand(5, 15);
            $currentBalance = 100.00;

            for ($i = 0; $i < $numTransactions; $i++) {
                $meme = $memes->random();
                $isBuy = rand(0, 1) === 1 || $currentBalance < 50; // More likely to buy if low balance
                
                if ($isBuy) {
                    $quantity = rand(1, 20);
                    $pricePerShare = $meme->current_price * rand(85, 115) / 100;
                    $subtotal = $quantity * $pricePerShare;
                    $feeAmount = $subtotal * $taxRate;
                    $totalAmount = $subtotal + $feeAmount;
                    
                    // Skip if not enough balance
                    if ($totalAmount > $currentBalance) {
                        continue;
                    }
                    
                    $currentBalance -= $totalAmount;

                    Transaction::create([
                        'user_id' => $trader->id,
                        'meme_id' => $meme->id,
                        'type' => 'buy',
                        'quantity' => $quantity,
                        'price_per_share' => round($pricePerShare, 4),
                        'fee_amount' => round($feeAmount, 2),
                        'total_amount' => round($totalAmount, 2),
                        'cfu_balance_after' => round($currentBalance, 2),
                        'executed_at' => now()->subDays(rand(1, 30))->subHours(rand(0, 23)),
                    ]);
                } else {
                    // Sell
                    $quantity = rand(1, 10);
                    $pricePerShare = $meme->current_price * rand(85, 115) / 100;
                    $subtotal = $quantity * $pricePerShare;
                    $feeAmount = $subtotal * $taxRate;
                    $totalAmount = $subtotal - $feeAmount;
                    
                    $currentBalance += $totalAmount;

                    Transaction::create([
                        'user_id' => $trader->id,
                        'meme_id' => $meme->id,
                        'type' => 'sell',
                        'quantity' => $quantity,
                        'price_per_share' => round($pricePerShare, 4),
                        'fee_amount' => round($feeAmount, 2),
                        'total_amount' => round($totalAmount, 2),
                        'cfu_balance_after' => round($currentBalance, 2),
                        'executed_at' => now()->subDays(rand(1, 30))->subHours(rand(0, 23)),
                    ]);
                }
            }
        }
    }
}
