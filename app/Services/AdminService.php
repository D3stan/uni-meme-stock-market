<?php

namespace App\Services;

use App\Models\Financial\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminService
{
    /**
     * Get transactions ordered by most recent first.
     * 
     * @param string $type Transaction type filter (all, buy, sell, bonus, dividend, listing_fee)
     * @param int $perPage Number of transactions per page
     * @return LengthAwarePaginator
     */
    public function getTransactions(string $type = 'all', int $perPage = 50): LengthAwarePaginator
    {
        $query = Transaction::with(['user', 'meme']);

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        return $query->orderBy('executed_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get transaction statistics.
     * 
     * @return array
     */
    public function getTransactionStats(): array
    {
        $total = Transaction::count();
        $totalBuy = Transaction::where('type', 'buy')->count();
        $totalSell = Transaction::where('type', 'sell')->count();
        $totalBonus = Transaction::where('type', 'bonus')->count();
        $totalDividend = Transaction::where('type', 'dividend')->count();
        $totalVolume = Transaction::whereIn('type', ['buy', 'sell'])->sum('total_amount');

        return [
            'total' => $total,
            'buy' => $totalBuy,
            'sell' => $totalSell,
            'bonus' => $totalBonus,
            'dividend' => $totalDividend,
            'volume' => round($totalVolume, 2),
        ];
    }
}
