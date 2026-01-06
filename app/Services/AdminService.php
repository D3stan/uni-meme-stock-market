<?php

namespace App\Services;

use App\Models\Market\Meme;
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

    /**
     * Get market communications ordered by most recent first.
     * 
     * @param string $filter Communication filter (all, active, expired)
     * @param int $perPage Number of communications per page
     * @return LengthAwarePaginator
     */
    public function getMarketCommunications(string $filter = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = MarketCommunication::with('admin');

        switch ($filter) {
            case 'active':
                $query->active();
                break;
            case 'expired':
                $query->expired();
                break;
        }

        return $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get market communication statistics.
     * 
     * @return array
     */
    public function getMarketCommunicationStats(): array
    {
        $total = MarketCommunication::count();
        $totalActive = MarketCommunication::active()->count();
        $totalExpired = MarketCommunication::expired()->count();
        $totalPermanent = MarketCommunication::whereNull('expires_at')->count();

        return [
            'total' => $total,
            'active' => $totalActive,
            'expired' => $totalExpired,
            'permanent' => $totalPermanent,
        ];
    }

    /**
     * Update market communication.
     * 
     * @param int $id
     * @param array $data
     * @return MarketCommunication
     */
    public function updateMarketCommunication(int $id, array $data): MarketCommunication
    {
        $communication = MarketCommunication::findOrFail($id);
        $communication->update($data);

        return $communication;
    }

    /**
     * Create new market communication.
     * 
     * @param array $data
     * @return MarketCommunication
     */
    public function createMarketCommunication(array $data): MarketCommunication
    {
        return MarketCommunication::create($data);
    }

    /**
     * Get memes for moderation.
     * 
     * @param string $filter Filter type (all, pending, approved, suspended)
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getMemes(string $filter = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = Meme::with(['creator', 'category', 'approvedBy']);

        switch ($filter) {
            case 'pending':
                $query->pending();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'suspended':
                $query->suspended();
                break;
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get meme statistics for moderation.
     * 
     * @return array
     */
    public function getMemeStats(): array
    {
        $total = Meme::count();
        $pending = Meme::pending()->count();
        $approved = Meme::approved()->count();
        $suspended = Meme::suspended()->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'suspended' => $suspended,
        ];
    }

    /**
     * Approve a meme.
     * 
     * @param int $id
     * @param int $adminId
     * @return Meme
     */
    public function approveMeme(int $id, int $adminId): Meme
    {
        $meme = Meme::findOrFail($id);
        $meme->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $adminId,
        ]);

        return $meme;
    }

    /**
     * Reject (suspend) a meme.
     * 
     * @param int $id
     * @return Meme
     */
    public function rejectMeme(int $id): Meme
    {
        $meme = Meme::findOrFail($id);
        $meme->update([
            'status' => 'suspended',
        ]);

        return $meme;
    }
}
