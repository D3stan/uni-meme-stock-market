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
    Route::get('/portfolio', fn() => redirect('/test-navbar'))->name('portfolio');
    Route::get('/create', fn() => redirect('/test-navbar'))->name('create');
    Route::get('/leaderboard', fn() => redirect('/test-navbar'))->name('leaderboard');
    Route::get('/profile', fn() => redirect('/test-navbar'))->name('profile');
});

// Test routes (can be removed in production)
Route::get('/test-components', function () {
    return view('test-components');
});

Route::get('/test-navbar', function () {
    return view('test-navbar');
});

// Trade route (placeholder)
Route::get('/trade/{id}', function ($id) {
    return view('pages.trade-station', ['memeId' => $id]);
})->name('trade');

// Debug (to be removed)
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('market');