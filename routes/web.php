<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\CctvController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(session()->has('admin')) {
        return redirect()->route('dashboard');
    } else {
        return view('welcome');
    }
});

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

    // Guardians — full resource
    Route::resource('guardians', GuardianController::class);

    // Activity schedule — full resource
    Route::resource('activities', ActivityController::class);

    // Alerts — read-only (system-generated from activity_history)
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');

    // Reports / analytics
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

});
