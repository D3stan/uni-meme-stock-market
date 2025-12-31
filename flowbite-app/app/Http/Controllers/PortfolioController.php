<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Display user's portfolio page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's holdings with meme data
        $holdings = $user->portfolios()
            ->with(['meme' => function ($query) {
                $query->with('creator');
            }])
            ->get()
            ->map(function ($portfolio) {
                $meme = $portfolio->meme;
                
                // Calculate 24h price change
                $oldPrice = $meme->priceHistories()
                    ->where('recorded_at', '>=', now()->subDay())
                    ->orderBy('recorded_at')
                    ->first();
                
                $change24h = 0;
                $change24hValue = 0;
                if ($oldPrice && $oldPrice->price > 0) {
                    $change24h = (($meme->current_price - $oldPrice->price) / $oldPrice->price) * 100;
                    $change24hValue = ($meme->current_price - $oldPrice->price) * $portfolio->quantity;
                }
                
                return [
                    'id' => $portfolio->id,
                    'meme_id' => $meme->id,
                    'ticker' => $meme->ticker,
                    'title' => $meme->title,
                    'image_path' => $meme->image_path,
                    'creator' => $meme->creator,
                    'quantity' => $portfolio->quantity,
                    'current_price' => (float) $meme->current_price,