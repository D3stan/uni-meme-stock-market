<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\AuthController;

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

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('market');
    Route::get('/portfolio', [MarketplaceController::class, 'portfolio'])->name('portfolio');
    Route::get('/create', fn() => redirect('/test-navbar'))->name('create');
    Route::get('/leaderboard', [MarketplaceController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/profile', [MarketplaceController::class, 'profile'])->name('profile');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () { return view('pages.admin.admin'); })->name('admin');
    Route::get('/events', function () { return view('pages.admin.events'); })->name('events');
    Route::get('/ledger', function () { return view('pages.admin.ledger'); })->name('ledger');
    Route::get('/moderation', function () { return view('pages.admin.moderation'); })->name('moderation');
    Route::get('/notifications', function () { return view('pages.admin.notifications'); })->name('notifications');
});

// Trade route (placeholder)
Route::get('/trade/{id}', function ($id) {
    return view('pages.trade-station', ['memeId' => $id]);
})->name('trade');

// Test routes (can be removed in production)
Route::get('/test-components', function () {
    return view('test-components');
});

Route::get('/test-navbar', function () {
    return view('test-navbar');
});

// Debug (to be removed)
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('market');