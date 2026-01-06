<?php

namespace App\Http\Controllers;

use App\Services\MarketService;
use App\Services\UserService;
use App\Models\Financial\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MarketplaceController extends Controller
{
    protected MarketService $marketService;
    protected UserService $userService;

    public function __construct(MarketService $marketService, UserService $userService)
    {
        $this->marketService = $marketService;
        $this->userService = $userService;
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

        // Get user balance
        $balance = Auth::user()->cfu_balance;

        return view('pages.appshell.marketplace', [
            'memes' => $memes,
            'tickerMemes' => $tickerMemes,
            'filter' => $filter,
            'balance' => $balance,
        ]);
    }

    /**
     * AJAX endpoint for infinite scroll meme loading
     */
    public function ajaxMemes(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        $validFilters = ['all', 'top_gainer', 'new_listing', 'high_risk'];
        if (!in_array($filter, $validFilters)) {
            $filter = 'all';
        }

        // Recupera i meme dal MarketService (come in index)
        $memes = $this->marketService->getMarketplaceMemes($filter, $perPage, $page);
        $memes->appends(['filter' => $filter]);

        // Per ogni meme, aggiungi html renderizzato
        $memesWithHtml = collect($memes->items())->map(function($meme) {
            return array_merge($meme, [
                'html' => view('components.meme.card', [
                    'image' => $meme['image'],
                    'alt' => $meme['text_alt'],
                    'name' => $meme['name'],
                    'ticker' => $meme['ticker'],
                    'price' => $meme['price'],
                    'change' => $meme['change'],
                    'creatorAvatar' => $meme['creatorAvatar'],
                    'creatorName' => $meme['creatorName'],
                    'status' => $meme['status'],
                    'tradeUrl' => route('trade', ['meme' => $meme['id']]),
                ])->render()
            ]);
        });

        return response()->json([
            'data' => $memesWithHtml,
            'current_page' => $memes->currentPage(),
            'last_page' => $memes->lastPage(),
            'next_page_url' => $memes->nextPageUrl(),
            'prev_page_url' => $memes->previousPageUrl(),
            'total' => $memes->total(),
        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        
        // Load user's badges with pivot data
        $badges = $user->badges()
            ->orderBy('user_badges.awarded_at', 'desc')
            ->get();
        
        // Registration date formatted
        $registrationDate = $user->created_at->format('M Y');
        
        // Total trades count (buy + sell transactions)
        $totalTrades = $user->transactions()
            ->whereIn('type', ['buy', 'sell'])
            ->count();
        
        // Badge count (badges earned by the user)
        $badgeCount = $user->badges()->count();
        
        // Meme count (memes created by the user)
        $memeCount = $user->createdMemes()->count();
        
        // Unread notifications count (only user-specific notifications)
        $unreadNotifications = $user->notifications()
            ->where(function($query) {
                $query->where('is_read', false)
                      ->orWhereNull('is_read');
            })
            ->count();
        
        // Format balance for top bar
        $balance = $user->cfu_balance;

        $isAdmin = $user->isAdmin();

        return view('pages.appshell.profile', [
            'balance' => $balance,
            'user' => $user,
            'badges' => $badges,
            'registrationDate' => $registrationDate,
            'totalTrades' => $totalTrades,
            'badgeCount' => $badgeCount,
            'memeCount' => $memeCount,
            'isAdmin' => $isAdmin,
            'unreadNotifications' => $unreadNotifications,
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
        $balance = $user->cfu_balance;
        
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
        $currentUser = Auth::user();
        
        // Get all traders (non-admin users) with their portfolio values
        $allUsers = User::where('role', '!=', 'admin')
            ->with('portfolios.meme:id,current_price')
            ->get()
            ->map(function ($user) {
                // Calculate net worth on-the-fly if cached value is null or 0
                $liquidBalance = (float) $user->cfu_balance;
                $investedValue = $user->portfolios->sum(function ($portfolio) {
                    return $portfolio->quantity * ($portfolio->meme->current_price ?? 0);
                });
                $user->calculated_net_worth = $liquidBalance + $investedValue;
                return $user;
            })
            ->sortByDesc('calculated_net_worth')
            ->values();
        
        // Calculate total users for percentile
        $totalUsers = $allUsers->count();
        
        // Build rankings array with user data
        $rankings = $allUsers->map(function ($user, $index) use ($currentUser) {
            return [
                'rank' => $index + 1,
                'user_id' => $user->id,
                'username' => '@' . explode('@', $user->email)[0],
                'avatar' => $user->avatarUrl(),
                'net_worth' => $user->calculated_net_worth,
                'is_current_user' => $user->id === $currentUser->id,
            ];
        })->toArray();
        
        // Split top 3 for podium
        $topThree = array_slice($rankings, 0, 3);
        
        // Find current user's position
        $currentUserPosition = null;
        foreach ($rankings as $ranking) {
            if ($ranking['is_current_user']) {
                $currentUserPosition = $ranking;
                // Calculate percentile
                if ($totalUsers > 0) {
                    $percentile = ceil(($ranking['rank'] / $totalUsers) * 100);
                    $currentUserPosition['percentile'] = $percentile;
                }
                $currentUserPosition['has_badge'] = false; // TODO: Check if user has any badges
                break;
            }
        }
        
        // Get user balance
        $balance = $currentUser->cfu_balance;

        return view('pages.appshell.leaderboard', [
            'balance' => $balance,
            'topThree' => $topThree,
            'rankings' => $rankings,
            'currentUserPosition' => $currentUserPosition,
        ]);
    }
}
