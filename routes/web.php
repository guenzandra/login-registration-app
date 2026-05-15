<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

//navigation
Route::get('/admin/settings', function () {
    return view('admin.settings');
})->name('admin.settings');

Route::get('/admin/accounts', function () {
    return view('admin.accounts');
})->name('admin.accounts');


//logout route
Route::post('/welcome', function () {

    return redirect('/welcome');
})->name('logout');



Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/accounts/data', [AccountController::class, 'getUsers'])->name('accounts.data');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::patch('/accounts/{id}/status', [AccountController::class, 'updateStatus'])->name('accounts.update-status');
    Route::post('/accounts/bulk-delete', [AccountController::class, 'bulkDelete'])->name('accounts.bulk-delete');
});