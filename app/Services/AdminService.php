<?php

namespace App\Services;

use App\Models\Financial\Transaction;
use App\Models\Utility\Notification;
use App\Models\Admin\MarketCommunication;
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
    public function getTransactions(string $type = 'all', int $perPage = 20): LengthAwarePaginator
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

    /**
     * Get notifications ordered by most recent first.
     * 
     * @param string $filter Notification filter (all, read, unread, global)
     * @param int $perPage Number of notifications per page
     * @return LengthAwarePaginator
     */
    public function getNotifications(string $filter = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = Notification::with('user');

        switch ($filter) {
            case 'read':
                $query->read();
                break;
            case 'unread':
                $query->unread();
                break;
            case 'global':
                $query->whereNull('user_id');
                break;
        }

        return $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get notification statistics.
     * 
     * @return array
     */
    public function getNotificationStats(): array
    {
        $total = Notification::count();
        $totalRead = Notification::read()->count();
        $totalUnread = Notification::unread()->count();
        $totalGlobal = Notification::whereNull('user_id')->count();
        $totalPersonal = Notification::whereNotNull('user_id')->count();

        return [
            'total' => $total,
            'read' => $totalRead,
            'unread' => $totalUnread,
            'global' => $totalGlobal,
            'personal' => $totalPersonal,
        ];
    }
}
