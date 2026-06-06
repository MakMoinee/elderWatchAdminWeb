<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\CctvController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/login',  [AuthController::class,  'showLogin'])->name('login');
Route::post('/login', [LoginController::class,  'store']);
Route::get('/logout', [AuthController::class,   'logout'])->name('logout');

// ── Protected ──────────────────────────────────────────────────────────────
Route::middleware('auth.admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Caregivers — full resource
    Route::resource('caregivers', CaregiverController::class);

    // Patients — full resource
    Route::resource('patients', PatientController::class);

    // CCTV Devices — full resource
    Route::resource('cctv', CctvController::class);

    // Placeholders — to be replaced as modules are built
    Route::get('/reports',  fn () => abort(404))->name('reports.index');
    Route::get('/guardians',fn () => abort(404))->name('guardians.index');
    Route::get('/guardians/create', fn () => abort(404))->name('guardians.create');
    Route::get('/alerts',   fn () => abort(404))->name('alerts.index');

});
