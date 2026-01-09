<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreviewOrderRequest;
use App\Http\Requests\ExecuteOrderRequest;
use App\Exceptions\Market\SlippageExceededException;
use App\Models\Market\Meme;
use App\Models\Financial\Portfolio;
use App\Models\Financial\Transaction;
use App\Services\TradingService;
use App\Services\MarketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TradingController extends Controller
{
    public function __construct(public TradingService $tradingService, public MarketService $marketService)
    {
    }

    /**
     * Displays the trading station for a specific meme, including current holdings and risk analysis.
     *
     * @param Meme $meme
     * @return View
     */
    public function show(Meme $meme): View
    {
        $meme->load(['creator', 'category']);

        $userHoldings = Portfolio::where('user_id', Auth::id())
            ->where('meme_id', $meme->id)
            ->first();

        $priceChange24h = $this->calculate24hChange($meme);

        $risk = $this->marketService->isHighRiskMeme($meme);

        return view('pages.appshell.trade-station', [
            'meme' => $meme,
            'risk' => $risk,
            'userHoldings' => $userHoldings,
            'priceChange24h' => $priceChange24h,
        ]);
    }

    /**
     * Prepares an order preview by calculating potential costs and effects without executing the transaction.
     *
     * @param PreviewOrderRequest $request
     * @return JsonResponse
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
     * Executes a buy or sell order for a meme, handling potential slippage.
     *
     * @param ExecuteOrderRequest $request
     * @return JsonResponse
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
        } catch (SlippageExceededException $e) {
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
     * Retrieves historical price data for a meme, aggregated by the specified time period.
     *
     * @param Meme $meme
     * @param string $period
     * @return JsonResponse
     */
    public function getPriceHistory(Meme $meme, string $period = '1d'): JsonResponse
    {
        $config = match($period) {
            '1h' => ['interval' => 1, 'limit' => 50],   // Last 50 hours
            '4h' => ['interval' => 4, 'limit' => 50],   // Last ~8 days
            '1d' => ['interval' => 24, 'limit' => 50],  // Last ~50 days
            default => ['interval' => 24, 'limit' => 50],
        };

        $interval = $config['interval'];
        $limit = $config['limit'];
        $totalHours = $interval * $limit;

        $allRecords = $meme->priceHistories()
            ->where('recorded_at', '>=', now()->subHours($totalHours))
            ->orderBy('recorded_at', 'asc')
            ->get(['price', 'recorded_at']);

        $groupedData = [];
        $currentBucket = null;

        foreach ($allRecords as $record) {
            $bucketKey = floor($record->recorded_at->timestamp / ($interval * 3600));
            
            if ($currentBucket !== $bucketKey) {
                $currentBucket = $bucketKey;
            }
            
            $groupedData[$bucketKey] = [
                'time' => $record->recorded_at->timestamp,
                'value' => (float) $record->price,
            ];
        }

        $priceHistory = array_values(array_slice($groupedData, -$limit));

        return response()->json([
            'success' => true,
            'data' => $priceHistory,
        ]);
    }

    /**
     * Retrieves the authenticated user's current holdings for the specified meme.
     *
     * @param Meme $meme
     * @return JsonResponse
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
     * Retrieves current market data for a meme to support live updates.
     *
     * @param Meme $meme
     * @return JsonResponse
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
     * Calculates the absolute and percentage price change of a meme over the last 24 hours.
     *
     * @param Meme $meme
     * @return array
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
