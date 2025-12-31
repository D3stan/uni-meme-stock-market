<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Display user's portfolio page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's holdings with meme data
        $holdings = $user->portfolios()
            ->with(['meme' => function ($query) {
                $query->with('creator');
            }])
            ->get()
            ->map(function ($portfolio) {
                $meme = $portfolio->meme;
                
                // Calculate 24h price change
                $oldPrice = $meme->priceHistories()
                    ->where('recorded_at', '>=', now()->subDay())
                    ->orderBy('recorded_at')
                    ->first();
                
                $change24h = 0;
                $change24hValue = 0;
                if ($oldPrice && $oldPrice->price > 0) {
                    $change24h = (($meme->current_price - $oldPrice->price) / $oldPrice->price) * 100;
                    $change24hValue = ($meme->current_price - $oldPrice->price) * $portfolio->quantity;
                }
                
                return [
                    'id' => $portfolio->id,
                    'meme_id' => $meme->id,
                    'ticker' => $meme->ticker,
                    'title' => $meme->title,
                    'image_path' => $meme->image_path,
                    'creator' => $meme->creator,
                    'quantity' => $portfolio->quantity,
                    'current_price' => (float) $meme->current_price,
                    'current_value' => $portfolio->current_value,
                    'avg_buy_price' => (float) $portfolio->avg_buy_price,
                    'profit_loss' => $portfolio->profit_loss,
                    'profit_loss_percent' => $portfolio->profit_loss_percent,
                    'change_24h' => round($change24h, 1),
                    'change_24h_value' => round($change24hValue, 2),
                ];
            })
            ->sortByDesc('current_value')
            ->values();
        
        // Calculate portfolio totals
        $investedValue = $holdings->sum('current_value');
        $liquidBalance = (float) $user->cfu_balance;
        $totalValue = $investedValue + $liquidBalance;
        
        // Calculate invested percentage
        $investedPercent = $totalValue > 0 ? round(($investedValue / $totalValue) * 100) : 0;
        $liquidPercent = 100 - $investedPercent;
        
        // Calculate total profit/loss from initial 100 CFU
        $initialBalance = 100; // Starting bonus
        $totalGain = $totalValue - $initialBalance;
        $totalGainPercent = $initialBalance > 0 ? (($totalValue - $initialBalance) / $initialBalance) * 100 : 0;
        
        // Get recent transactions
        $recentTransactions = $user->transactions()
            ->with('meme')
            ->orderByDesc('executed_at')
            ->limit(10)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'meme_ticker' => $transaction->meme?->ticker,
                    'meme_title' => $transaction->meme?->title,
                    'meme_image' => $transaction->meme?->image_path,
                    'quantity' => $transaction->quantity,
                    'price_per_share' => (float) $transaction->price_per_share,
                    'total_amount' => (float) $transaction->total_amount,
                    'executed_at' => $transaction->executed_at,
                ];
            });
        
        return view('pages.app.portfolio.index', compact(
            'holdings',
            'totalValue',
            'investedValue',
            'liquidBalance',
            'investedPercent',
            'liquidPercent',
            'totalGain',
            'totalGainPercent',
            'recentTransactions'
        ));
    }
}
