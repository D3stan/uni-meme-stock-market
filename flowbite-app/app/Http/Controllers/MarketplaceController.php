<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    /**
     * Display the marketplace with meme listings
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');
        $search = $request->query('search');

        $query = Meme::with(['creator', 'category'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('trading_starts_at')
                    ->orWhere('trading_starts_at', '<=', now());
            });

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('ticker', 'like', "%{$search}%");
            });
        }

        // Apply category/type filters
        switch ($filter) {
            case 'trending':
                // Order by 24h volume (calculated from recent transactions)
                $query->orderByDesc('circulating_supply');
                break;
            case 'new':
                $query->orderByDesc('approved_at');
                break;
            case 'top-gainers':
                // Order by highest positive price change
                $query->orderByDesc('current_price');
                break;
            case 'top-losers':
                // Order by lowest price
                $query->orderBy('current_price');
                break;
            case 'high-risk':
                // High slope = high volatility
                $query->orderByDesc('slope');
                break;
            default:
                $query->orderByDesc('circulating_supply');
        }

        $memes = $query->paginate(20);

        // Calculate 24h change for each meme
        $memes->getCollection()->transform(function ($meme) {
            $oldPriceRecord = $meme->priceHistories()
                ->where('recorded_at', '>=', now()->subDay())
                ->orderBy('recorded_at')
                ->first();

            $change24h = 0;
            if ($oldPriceRecord && $oldPriceRecord->price > 0) {
                $change24h = (($meme->current_price - $oldPriceRecord->price) / $oldPriceRecord->price) * 100;
            }

            $meme->change_24h = round($change24h, 1);

            // Calculate 24h volume
            $volume24h = $meme->transactions()
                ->where('executed_at', '>=', now()->subDay())
                ->sum('total_amount');
            $meme->volume_24h = $volume24h;

            return $meme;
        });

        return view('pages.app.marketplace.index', [
            'memes' => $memes,
            'currentFilter' => $filter,
            'searchQuery' => $search,
        ]);
    }

    /**
     * API endpoint for infinite scroll / AJAX loading
     */
    public function loadMore(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $page = $request->query('page', 1);

        $query = Meme::with(['creator'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('trading_starts_at')
                    ->orWhere('trading_starts_at', '<=', now());
            });

        switch ($filter) {
            case 'trending':
                $query->orderByDesc('circulating_supply');
                break;
            case 'new':
                $query->orderByDesc('approved_at');
                break;
            case 'top-gainers':
                $query->orderByDesc('current_price');
                break;
            case 'high-risk':
                $query->orderByDesc('slope');
                break;
            default:
                $query->orderByDesc('circulating_supply');
        }

        $memes = $query->paginate(20, ['*'], 'page', $page);

        $memes->getCollection()->transform(function ($meme) {
            $oldPriceRecord = $meme->priceHistories()
                ->where('recorded_at', '>=', now()->subDay())
                ->orderBy('recorded_at')
                ->first();

            $change24h = 0;
            if ($oldPriceRecord && $oldPriceRecord->price > 0) {
                $change24h = (($meme->current_price - $oldPriceRecord->price) / $oldPriceRecord->price) * 100;
            }

            $meme->change_24h = round($change24h, 1);

            return $meme;
        });

        return response()->json([
            'memes' => $memes->items(),
            'hasMore' => $memes->hasMorePages(),
            'nextPage' => $memes->currentPage() + 1,
        ]);
    }
}
