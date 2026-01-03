<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-components', function () {
    return view('test-components');
});

Route::get('/test-navbar', function () {
    return view('test-navbar');
});

// Placeholder routes for navigation (to avoid 404 errors in test)
Route::get('/market', fn() => redirect('/test-navbar'))->name('market');
Route::get('/portfolio', fn() => redirect('/test-navbar'))->name('portfolio');
Route::get('/create', fn() => redirect('/test-navbar'))->name('create');
Route::get('/leaderboard', fn() => redirect('/test-navbar'))->name('leaderboard');
Route::get('/profile', fn() => redirect('/test-navbar'))->name('profile');
