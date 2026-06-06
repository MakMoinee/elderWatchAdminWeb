<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (dashboard and beyond) go here later
Route::middleware('auth.admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard coming soon';
    })->name('dashboard');
});
