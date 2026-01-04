<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreviewOrderRequest;
use App\Http\Requests\ExecuteOrderRequest;
use App\Models\Market\Meme;
use App\Models\Financial\Portfolio;
use App\Models\Financial\Transaction;
use App\Services\TradingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TradingController extends Controller
{
    public function __construct(public TradingService $tradingService)
    {
    }

    /**
     * Display the Trade Station page for a specific meme.
     */
    public function show(Meme $meme): View
    {
        // Load relationships for the view
        $meme->load(['creator', 'category']);

        // Get user's current holdings for this meme
        $userHoldings = Portfolio::where('user_id', Auth::id())
            ->where('meme_id', $meme->id)
            ->first();

        // Calculate 24h price change
        $priceChange24h = $this->calculate24hChange($meme);

        return view('pages.trade-station', [
            'meme' => $meme,
            'userHoldings' => $userHoldings,
            'priceChange24h' => $priceChange24h,
        ]);
    }

    /**
     * Preview an order (calculate costs without executing).
     */
    public function preview(PreviewOrderRequest $request): JsonResponse
    {
        $meme = Meme::findOrFail($request->meme_id);
        
        try {
            $preview = $this->tradingService->previewOrder(
                $meme,
                $request->type,
                $request->quantity
            );

            return response()->json([
                'success' => true,
                'data' => $preview,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Execute a trade (buy or sell).
     */
    public function execute(ExecuteOrderRequest $request): JsonResponse
    {
        $meme = Meme::findOrFail($request->meme_id);
        $user = Auth::user();

        try {
            if ($request->type === 'buy') {
                $transaction = $this->tradingService->executeBuy(
                    $user,
                    $meme,
                    $request->quantity,
                    $request->expected_total
                );
            } else {
                $transaction = $this->tradingService->executeSell(
                    $user,
                    $meme,
                    $request->quantity,
                    $request->expected_total
                );
            }

            // Reload user to get updated balance
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => $request->type === 'buy' 
                    ? "Acquisto completato! +{$request->quantity} azioni {$meme->ticker}"
                    : "Vendita completata! -{$request->quantity} azioni {$meme->ticker}",
                'data' => [
                    'transaction' => $transaction,
                    'new_balance' => $user->cfu_balance,
                    'new_price' => $meme->current_price,
                ],
            ]);
        } catch (\App\Exceptions\Market\SlippageExceededException $e) {
            // Slippage detected - client should re-preview
            return response()->json([
                'success' => false,
                'slippage_detected' => true,
                'message' => $e->getMessage(),
                'data' => [
                    'expected_total' => $request->expected_total,
                    'actual_total' => $e->getActualTotal(),
                ],
            ], 409); // 409 Conflict
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get price history for chart rendering.
     */
    public function getPriceHistory(Meme $meme, string $period = '1d'): JsonResponse
    {
        $hours = match($period) {
            '1h' => 1,
            '4h' => 4,
            '1d' => 24,
            default => 24,
        };

        $priceHistory = $meme->priceHistories()
            ->where('recorded_at', '>=', now()->subHours($hours))
            ->orderBy('recorded_at', 'asc')
            ->get(['price', 'recorded_at'])
            ->map(fn($point) => [
                'time' => $point->recorded_at->timestamp,
                'value' => (float) $point->price,
            ]);

        return response()->json([
            'success' => true,
            'data' => $priceHistory,
        ]);
    }

    /**
     * Get user's current holdings for a specific meme.
     */
    public function getCurrentHoldings(Meme $meme): JsonResponse
    {
        $portfolio = Portfolio::where('user_id', Auth::id())
            ->where('meme_id', $meme->id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'quantity' => $portfolio ? $portfolio->quantity : 0,
                'avg_buy_price' => $portfolio ? (float) $portfolio->avg_buy_price : 0,
                'current_value' => $portfolio 
                    ? $portfolio->quantity * $meme->current_price 
                    : 0,
            ],
        ]);
    }

    /**
     * Get current market data for polling updates.
     */
    public function getMarketData(Meme $meme): JsonResponse
    {
        $priceChange24h = $this->calculate24hChange($meme);

        return response()->json([
            'success' => true,
            'data' => [
                'current_price' => (float) $meme->current_price,
                'circulating_supply' => $meme->circulating_supply,
                'price_change_24h' => $priceChange24h,
            ],
        ]);
    }

    /**
     * Calculate 24h price change percentage.
     */
    private function calculate24hChange(Meme $meme): array
    {
        $price24hAgo = $meme->priceHistories()
            ->where('recorded_at', '<=', now()->subHours(24))
            ->orderBy('recorded_at', 'desc')
            ->first();

        if (!$price24hAgo) {
            return [
                'absolute' => 0,
                'percentage' => 0,
            ];
        }

        $change = $meme->current_price - $price24hAgo->price;
        $percentage = ($change / $price24hAgo->price) * 100;

        return [
            'absolute' => round($change, 2),
            'percentage' => round($percentage, 2),
        ];
    }
}
