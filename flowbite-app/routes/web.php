<?php

use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.page');

Route::get('/register', function () {
    return view('auth.register');
})->name('register.page');

// Authentication routes
Route::post('/api/register', [RegisterController::class, 'register'])->name('register');
Route::post('/api/login', [LoginController::class, 'login'])->name('login');
Route::post('/api/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/portfolio', function () {
        return view('portfolio.index');
    })->name('portfolio');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile');
});

// User profile API routes (protected)
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::patch('/user/profile', [UserProfileController::class, 'update'])->name('profile.update');
});
