<?php

namespace App\Http\Controllers;

use App\Services\MarketService;
use App\Services\UserService;
use App\Models\Financial\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        $memes = $this->marketService->getMarketplaceMemes($filter, 5);

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
        $perPage = $request->get('per_page', 5);

        $validFilters = ['all', 'top_gainer', 'new_listing', 'high_risk'];
        if (!in_array($filter, $validFilters)) {
            $filter = 'all';
        }

        // Recupera i meme dal MarketService (come in index)
        $memes = $this->marketService->getMarketplaceMemes($filter, $perPage);
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
            ->get();
            
        // Fetch 24h prices for all memes in portfolio to calculate daily change
        $memeIds = $positions->pluck('meme_id')->toArray();
        $prices24h = collect();
        
        if (!empty($memeIds)) {
            $prices24h = \App\Models\Market\Meme::whereIn('memes.id', $memeIds)
                ->leftJoin('price_histories as ph_24h', function ($join) {
                    $join->on('ph_24h.id', '=', \Illuminate\Support\Facades\DB::raw("
                        (SELECT id FROM price_histories 
                         WHERE meme_id = memes.id 
                         AND recorded_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                         ORDER BY recorded_at ASC, id ASC
                         LIMIT 1)
                    "));
                })
                ->select(
                    'memes.id',
                    \Illuminate\Support\Facades\DB::raw('COALESCE(ph_24h.price, memes.base_price) as price_24h_ago')
                )
                ->pluck('price_24h_ago', 'id');
        }
        
        $totalInvested = 0;
        $portfolioStartValue = 0;

        $positions = $positions->map(function ($position) use ($prices24h, &$totalInvested, &$portfolioStartValue) {
            $meme = $position->meme;
            $currentPrice = $meme->current_price;
            $price24hAgo = $prices24h->get($meme->id) ?? $meme->base_price;
            
            $currentValue = $position->quantity * $currentPrice;
            $startValue = $position->quantity * $price24hAgo;
            
            $totalInvested += $currentValue;
            $portfolioStartValue += $startValue;

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
        
        // Get liquid balance
        $liquidBalance = $user->cfu_balance;
        
        // Calculate net worth
        $netWorth = $liquidBalance + $totalInvested;
        $startNetWorth = $liquidBalance + $portfolioStartValue;
        
        // Calculate daily change
        $dailyChange = $totalInvested - $portfolioStartValue;
        $dailyChangePct = $startNetWorth > 0 ? ($dailyChange / $startNetWorth) * 100 : 0;
        
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
        
        // Get filter from request (default: 'all')
        $period = $request->get('period', 'all');
        
        // Validate period
        $validPeriods = ['all', 'week', 'month'];
        if (!in_array($period, $validPeriods)) {
            $period = 'all';
        }
        
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
                
                // Get most recent badge
                Log::info($currentUser->badges()->orderBy('user_badges.awarded_at', 'desc')->first());
                $recentBadge = $currentUser->badges()
                    ->orderBy('user_badges.awarded_at', 'desc')
                    ->first();
                    
                $currentUserPosition['recent_badge'] = $recentBadge ? [
                    'name' => $recentBadge->name,
                    'icon_path' => $recentBadge->icon_path,
                    'description' => $recentBadge->description,
                ] : null;
                
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
            'period' => $period,
        ]);
    }
}
