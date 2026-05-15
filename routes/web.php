<?php

use Illuminate\Support\Facades\Route;

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