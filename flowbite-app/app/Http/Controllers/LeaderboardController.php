<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard
     */
    public function index(Request $request): View
    {
        $period = $request->query('period', 'all');

        // Get all traders (non-admin users) with their portfolios
        $users = User::where('role', 'trader')
            ->where('is_suspended', false)
            ->with(['portfolios.meme'])
            ->get()
            ->map(function ($user) {
                // Calculate portfolio value
                $portfolioValue = $user->portfolios->sum(function ($portfolio) {
                    return $portfolio->quantity * ($portfolio->meme->current_price ?? 0);
                });

                // Net worth = CFU balance + portfolio value
                $user->net_worth = $user->cfu_balance + $portfolioValue;
                $user->portfolio_value = $portfolioValue;

                // Calculate gain percentage (from initial 100 CFU bonus)
                $initialBalance = 100; // Initial bonus
                $user->gain_percentage = $initialBalance > 0 
                    ? (($user->net_worth - $initialBalance) / $initialBalance) * 100 
                    : 0;

                return $user;
            })
            ->sortByDesc('net_worth')
            ->values();

        // Get top 3 for podium
        $podium = $users->take(3);

        // Get rest of leaderboard (positions 4+)
        $restOfLeaderboard = $users->slice(3)->take(17); // Show up to position 20

        // Find current user's position
        $currentUser = auth()->user();
        $currentUserRank = $users->search(function ($user) use ($currentUser) {
            return $user->id === $currentUser->id;
        });
        $currentUserRank = $currentUserRank !== false ? $currentUserRank + 1 : null;

        // Get current user's net worth data
        $currentUserData = $users->firstWhere('id', $currentUser->id);

        return view('pages.app.leaderboard.index', [
            'podium' => $podium,
            'leaderboard' => $restOfLeaderboard,
            'currentUserRank' => $currentUserRank,
            'currentUserData' => $currentUserData,
            'currentPeriod' => $period,
            'totalTraders' => $users->count(),
        ]);
    }
}
