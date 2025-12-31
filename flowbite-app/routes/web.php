<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\PortfolioController;
use App\Models\Meme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/marketplace');
    }

    // Get top memes by circulating supply (most traded)
    $topMemes = Meme::where('status', 'approved')
        ->orderByDesc('circulating_supply')
        ->limit(4)
        ->get()
        ->map(function ($meme, $index) {
            // Calculate 24h change from price history
            $oldPrice = $meme->priceHistories()
                ->where('recorded_at', '>=', now()->subDay())
                ->orderBy('recorded_at')
                ->first();
            
            $change = 0;
            if ($oldPrice && $oldPrice->price > 0) {
                $change = (($meme->current_price - $oldPrice->price) / $oldPrice->price) * 100;
            }

            return [
                'rank' => $index + 1,
                'id' => $meme->id,
                'ticker' => $meme->ticker,
                'title' => $meme->title,
                'volume' => number_format($meme->circulating_supply * $meme->current_price, 1) . ' CFU',
                'change' => round($change, 1),
                'image_path' => $meme->image_path,
            ];
        });

    return view('pages.landing', compact('topMemes'));
})->name('home');

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login.page');

Route::get('/register', function () {
    return view('pages.auth.register');
})->name('register.page');

Route::get('/verify-otp', function () {
    return view('pages.auth.verify-otp');
})->name('verify-otp.page');

Route::get('/onboarding', function () {
    return view('pages.auth.onboarding');
})->middleware('auth')->name('onboarding');

// Authentication routes
Route::post('/api/register', [RegisterController::class, 'register'])->name('register');
Route::post('/api/login', [LoginController::class, 'login'])->name('login');
Route::post('/api/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// OTP verification routes
Route::post('/api/verify-otp', [OtpController::class, 'verify'])->name('verify-otp');
Route::post('/api/resend-otp', [OtpController::class, 'resend'])->name('resend-otp');

// Protected routes - App Shell pages
Route::middleware('auth')->group(function () {
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
    Route::get('/api/marketplace/load-more', [MarketplaceController::class, 'loadMore'])->name('marketplace.load-more');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');

    Route::get('/profile', function () {
        return view('pages.app.profile.index');
    })->name('profile');

    Route::get('/meme/create', function () {
        // TODO: Implement meme creation page
        return view('pages.app.marketplace.index');
    })->name('meme.create');

    Route::get('/meme/{meme}', function (Meme $meme) {
        // TODO: Implement meme detail/trading page
        return view('pages.app.marketplace.index');
    })->name('meme.show');
});
