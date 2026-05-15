<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Logout route
Route::post('/', function () {
    return redirect('welcome');
})->name('logout');

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics'])->name('dashboard.statistics');
    Route::get('/dashboard/activity', [DashboardController::class, 'getRecentActivity'])->name('dashboard.activity');
    Route::get('/dashboard/export', [DashboardController::class, 'exportReport'])->name('dashboard.export');

    // Accounts management routes
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/accounts/data', [AccountController::class, 'getUsers'])->name('accounts.data');
    Route::get('/accounts/statistics', [AccountController::class, 'getStatistics'])->name('accounts.statistics');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::patch('/accounts/{id}/status', [AccountController::class, 'updateStatus'])->name('accounts.update-status');
    Route::post('/accounts/bulk-delete', [AccountController::class, 'bulkDelete'])->name('accounts.bulk-delete');

    // Settings route
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});

// Fallback route for undefined admin routes (optional)
Route::fallback(function () {
    return redirect()->route('admin.dashboard');
});
