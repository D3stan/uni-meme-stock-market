<?php

namespace App\Http\Controllers;

use App\Services\MarketService;
use App\Models\Financial\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    protected MarketService $marketService;

    public function __construct(MarketService $marketService)
    {
        $this->marketService = $marketService;
    }

    /**
     * Display the marketplace page with memes.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get filter from request (default: 'all')
        $filter = $request->get('filter', 'all');
        
        // Validate filter
        $validFilters = ['all', 'top_gainer', 'new_listing', 'high_risk'];
        if (!in_array($filter, $validFilters)) {
            $filter = 'all';
        }

        // Get memes from market service
        $memes = $this->marketService->getMarketplaceMemes($filter, 20);

        // Get ticker data for top movers
        $tickerMemes = $this->marketService->getTickerMemes(15);

        // Get user balance (mock for now)
        $balance = '1,250.00';

        return view('pages.appshell.marketplace', [
            'memes' => $memes,
            'tickerMemes' => $tickerMemes,
            'filter' => $filter,
            'balance' => $balance,
        ]);
    }

    public function profile(Request $request)
    {
        // Get ticker data for top movers
        $tickerMemes = $this->marketService->getTickerMemes(15);

        // Get user balance (mock for now)
        $balance = '1,250.00';

        return view('pages.appshell.profile', [
            'balance' => $balance,
            'tickerMemes' => $tickerMemes,
        ]);
    }

    public function portfolio(Request $request)
    {
        $user = Auth::user();
        
        // Get user's portfolio positions with meme details
        $positions = Portfolio::where('user_id', $user->id)
            ->with(['meme.category'])
            ->get()
            ->map(function ($position) {
                $currentValue = $position->quantity * $position->meme->current_price;
                $costBasis = $position->quantity * $position->avg_buy_price;
                $pnlAmount = $currentValue - $costBasis;
                $pnlPct = $costBasis > 0 ? ($pnlAmount / $costBasis) * 100 : 0;
                
                return [
                    'meme' => $position->meme,
                    'quantity' => $position->quantity,
                    'avg_buy_price' => $position->avg_buy_price,
                    'current_value' => $currentValue,
                    'pnl_amount' => $pnlAmount,
                    'pnl_pct' => $pnlPct,
                ];
            });
        
        // Calculate total invested value
        $totalInvested = $positions->sum('current_value');
        
        // Get liquid balance
        $liquidBalance = $user->cfu_balance;
        
        // Calculate net worth
        $netWorth = $liquidBalance + $totalInvested;
        
        // Calculate daily change (mock for now - would need price_histories table)
        // TODO: Implement real daily change calculation using price_histories
        $dailyChange = $netWorth * 0.125; // Mock: +12.5%
        $dailyChangePct = 12.5; // Mock
        
        // Format balance for top bar
        $balance = number_format($user->cfu_balance, 2);
        
        // Get ticker data for top movers
        $tickerMemes = $this->marketService->getTickerMemes(15);

        return view('pages.appshell.portfolio', [
            'balance' => $balance,
            'tickerMemes' => $tickerMemes,
            'netWorth' => $netWorth,
            'dailyChange' => $dailyChange,
            'dailyChangePct' => $dailyChangePct,
            'liquidBalance' => $liquidBalance,
            'totalInvested' => $totalInvested,
            'positions' => $positions,
        ]);
    }

    public function leaderboard(Request $request)
    {
        // Get ticker data for top movers
        $tickerMemes = $this->marketService->getTickerMemes(15);

        // Get user balance (mock for now)
        $balance = '1,250.00';

        return view('pages.appshell.leaderboard', [
            'balance' => $balance,
            'tickerMemes' => $tickerMemes,
        ]);
    }
}
