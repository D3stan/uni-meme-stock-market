<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display user's profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's badges
        $badges = $user->badges()->get();
        
        // Calculate total trades
        $totalTrades = $user->transactions()
            ->whereIn('type', ['buy', 'sell'])
            ->count();
        
        // Get member since date
        $memberSince = $user->created_at;
        
        // Calculate global rank (by net worth)
        $netWorth = $user->calculateNetWorth();
        $globalRank = User::where('role', 'trader')
            ->get()
            ->map(fn($u) => ['id' => $u->id, 'net_worth' => $u->calculateNetWorth()])
            ->sortByDesc('net_worth')
            ->values()
            ->search(fn($item) => $item['id'] === $user->id);
        $globalRank = $globalRank !== false ? $globalRank + 1 : null;
        
        // Get best trade (highest profit percentage)
        $bestTrade = $this->getBestTrade($user);
        
        // Get unread notifications count
        $unreadNotifications = $user->notifications()
            ->where('is_read', false)
            ->count();
        
        return view('pages.app.profile.index', compact(
            'user',
            'badges',
            'totalTrades',
            'memberSince',
            'globalRank',
            'bestTrade',
            'unreadNotifications'
        ));
    }
    
    /**
     * Calculate user's best trade by profit percentage
     */
    private function getBestTrade($user)
    {
        // Get all sell transactions with their corresponding buy prices
        $portfolios = $user->portfolios()->with('meme')->get();
        
        $bestProfit = 0;
        $bestMeme = null;
        
        foreach ($portfolios as $portfolio) {
            if ($portfolio->avg_buy_price > 0) {
                $profitPercent = (($portfolio->meme->current_price - $portfolio->avg_buy_price) / $portfolio->avg_buy_price) * 100;
                if ($profitPercent > $bestProfit) {
                    $bestProfit = $profitPercent;
                    $bestMeme = $portfolio->meme;
                }
            }
        }
        
        if ($bestMeme) {
            return [
                'ticker' => $bestMeme->ticker,
                'profit_percent' => round($bestProfit, 0),
            ];
        }
        
        return null;
    }
}
