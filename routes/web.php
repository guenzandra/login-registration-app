<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;

// Welcome page
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-reset-code', [AuthController::class, 'sendResetCode']);
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/statistics', [AuthController::class, 'getStatistics']);

// Admin routes group
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics'])->name('dashboard.statistics');
    Route::get('/dashboard/activity', [DashboardController::class, 'getRecentActivity'])->name('dashboard.activity');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/accounts/data', [AccountController::class, 'getUsers'])->name('accounts.data');
    Route::get('/accounts/statistics', [AccountController::class, 'getStatistics'])->name('accounts.statistics');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::post('/accounts/bulk-delete', [AccountController::class, 'bulkDelete'])->name('accounts.bulk-delete');

    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});
