<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketplaceController;

Route::get('/', function () {
    return view('welcome');
});

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

// Navigation route
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('market');
Route::get('/portfolio', fn() => redirect('/test-navbar'))->name('portfolio');
Route::get('/create', fn() => redirect('/test-navbar'))->name('create');
Route::get('/leaderboard', fn() => redirect('/test-navbar'))->name('leaderboard');
Route::get('/profile', fn() => redirect('/test-navbar'))->name('profile');
