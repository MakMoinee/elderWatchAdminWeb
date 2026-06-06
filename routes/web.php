<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/login',  [AuthController::class,  'showLogin'])->name('login');
Route::post('/login', [LoginController::class,  'store']);
Route::get('/logout', [AuthController::class,   'logout'])->name('logout');

// ── Protected ──────────────────────────────────────────────────────────────
Route::middleware('auth.admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Placeholders — to be replaced with real controllers as modules are built
    Route::get('/reports',    fn () => abort(404))->name('reports.index');
    Route::get('/caregivers', fn () => abort(404))->name('caregivers.index');
    Route::get('/caregivers/create', fn () => abort(404))->name('caregivers.create');
    Route::get('/guardians',  fn () => abort(404))->name('guardians.index');
    Route::get('/guardians/create',  fn () => abort(404))->name('guardians.create');
    Route::get('/patients',   fn () => abort(404))->name('patients.index');
    Route::get('/patients/create',   fn () => abort(404))->name('patients.create');
    Route::get('/cctv',       fn () => abort(404))->name('cctv.index');
    Route::get('/cctv/create',       fn () => abort(404))->name('cctv.create');
    Route::get('/alerts',     fn () => abort(404))->name('alerts.index');

});
