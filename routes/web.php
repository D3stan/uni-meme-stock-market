<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TradingController;
use App\Http\Controllers\ProfileController;

// Guest routes (Landing page)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('market');
    }
    return view('pages.landing');
})->name('welcome');

// Authentication routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
    
    Route::get('/verify-otp', [AuthController::class, 'showOtpVerification'])->name('auth.verify-otp.show');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp.post');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
});

// Public avatar route (no auth required to view avatars)
Route::get('/storage/data/{userId}/{filename}', [ProfileController::class, 'serveAvatar'])
    ->where('userId', '[0-9]+')
    ->where('filename', 'avatar\.(jpg|jpeg|png|gif)')
    ->name('avatar.serve');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('market');
    Route::get('/portfolio', [MarketplaceController::class, 'portfolio'])->name('portfolio');
    Route::get('/create', [CreateController::class, 'create'])->name('create');
    Route::post('/meme/check-ticker', [CreateController::class, 'checkTicker'])->name('meme.check-ticker');
    Route::post('/meme/store', [CreateController::class, 'store'])->name('meme.store');
    Route::get('/leaderboard', [MarketplaceController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/profile', [MarketplaceController::class, 'profile'])->name('profile');

    // Profile settings routes
    Route::get('/profile/settings', [ProfileController::class, 'showSettings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
    Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');

    // Trading routes
    Route::get('/trade/{meme}', [TradingController::class, 'show'])->name('trade');
    
    // Trading API routes
    Route::prefix('api/trade')->name('api.trade.')->group(function () {
        Route::post('/preview', [TradingController::class, 'preview'])->name('preview');
        Route::post('/execute', [TradingController::class, 'execute'])->name('execute');
        Route::get('/{meme}/price-history/{period?}', [TradingController::class, 'getPriceHistory'])->name('price-history');
        Route::get('/{meme}/holdings', [TradingController::class, 'getCurrentHoldings'])->name('holdings');
        Route::get('/{meme}/market-data', [TradingController::class, 'getMarketData'])->name('market-data');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::post('/events', [AdminController::class, 'createEvent'])->name('events.create');
    Route::put('/events/{id}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::get('/ledger', [AdminController::class, 'ledger'])->name('ledger');
    Route::get('/moderation', [AdminController::class, 'moderation'])->name('moderation');
    Route::post('/moderation/{id}/approve', [AdminController::class, 'approveMeme'])->name('moderation.approve');
    Route::post('/moderation/{id}/reject', [AdminController::class, 'rejectMeme'])->name('moderation.reject');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
});

// Test routes (can be removed in production)
Route::get('/test-components', function () {
    return view('test-components');
});

Route::get('/test-navbar', function () {
    return view('test-navbar');
});
