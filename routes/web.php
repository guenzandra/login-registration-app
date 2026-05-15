<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;

// ============================================
// WELCOME PAGE ROUTE
// ============================================

// 'Route::get()' — handles HTTP GET requests
// Yung '/' means root URL ng website (e.g., https://yourwebsite.com/)
// [AuthController::class, 'showWelcome'] — ibig sabihin, tawagin yung 'showWelcome' method sa AuthController
// 'name('welcome')' — binigyan ko siya ng nickname na 'welcome' para madali siyang tawagin sa code
// Example: redirect()->route('welcome') or url()->route('welcome')
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');

// ============================================
// AUTHENTICATION ROUTES (walang middleware, public access)
// ============================================

// 'Route::post()' — for submitting data (forms, AJAX)
// POST dapat 'to kasi nagse-send tayo ng sensitive info like passwords
Route::post('/register', [AuthController::class, 'register']);        // User registration
Route::post('/login', [AuthController::class, 'login']);              // User login

// Password reset flow (3 steps):
Route::post('/send-reset-code', [AuthController::class, 'sendResetCode']);     // Step 1: Request reset code
Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode']); // Step 2: Verify the code
Route::post('/reset-password', [AuthController::class, 'resetPassword']);      // Step 3: Set new password

// 'Route::match(['GET', 'POST'], ...)' — accepts both GET and POST requests
// Bakit? Kasi pwedeng mag-logout by clicking a link (GET) or via form submission (POST)
// Flexible 'to para kahit anong method gamitin, gagana siya
Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

// GET request for fetching statistics — walang ginagalaw na data, view lang talaga
Route::get('/statistics', [AuthController::class, 'getStatistics']);

// ============================================
// ADMIN ROUTES (protected by auth middleware)
// ============================================

// 'Route::prefix('admin')' — adds '/admin' sa simula ng lahat ng routes sa loob
// So 'dashboard' becomes '/admin/dashboard', 'accounts' becomes '/admin/accounts', etc.
//
// 'name('admin.')' — adds 'admin.' prefix sa lahat ng route names
// So 'dashboard' becomes 'admin.dashboard', 'accounts' becomes 'admin.accounts', etc.
// Pwede mo siyang tawagin using route('admin.dashboard')
//
// 'middleware('auth')' — eto yung nagpo-protect! Hindi maa-access 'tong routes kung hindi naka-login
// Kasi nga admin pages 'to, dapat authenticated users lang, diba?
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // ----- DASHBOARD ROUTES -----
    // Main dashboard page — shows overview ng system
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AJAX endpoint for dashboard statistics — returns JSON data for charts/metrics
    // Separate 'to para hindi sabay-sabay mag-load, mas mabilis yung page experience
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics'])->name('dashboard.statistics');

    // AJAX endpoint for recent user activity — like recent logins, registrations, etc.
    Route::get('/dashboard/activity', [DashboardController::class, 'getRecentActivity'])->name('dashboard.activity');

    // ----- ACCOUNT MANAGEMENT ROUTES (CRUD operations) -----

    // Main accounts listing page (HTML view)
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');

    // Data endpoint for DataTables/AJAX — returns JSON list of users for the table
    // Bakit hiwalay? Para sa server-side processing ng DataTables, less load sa browser
    Route::get('/accounts/data', [AccountController::class, 'getUsers'])->name('accounts.data');

    // Statistics endpoint for accounts page — counts ng active/inactive/total users
    Route::get('/accounts/statistics', [AccountController::class, 'getStatistics'])->name('accounts.statistics');

    // CREATE — add new user (form submission)
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');

    // UPDATE — edit existing user (PUT request)
    // '{id}' is a route parameter — dinadaan sa method parameter ng controller
    // PUT gamit ko instead of POST kasi RESTful convention — updating existing resource
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');

    // DELETE — remove single user
    // DELETE method for RESTful API convention
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // DELETE — remove multiple users at once (bulk operation)
    // POST gamit ko instead of DELETE kasi mahirap mag-send ng array sa DELETE method via forms
    // So common practice 'to sa web apps — POST for bulk operations
    Route::post('/accounts/bulk-delete', [AccountController::class, 'bulkDelete'])->name('accounts.bulk-delete');

    // ----- SETTINGS PAGE -----
    // Simple route na diretso nagre-return ng view without a controller
    // Ginamit ko 'yung closure function() { return view('admin.settings'); }
    // For simple pages na walang complex logic, pwedeng ganito — shortcut lang, no need ng separate controller
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});
