<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| SECURITY: Routes are organized by access level:
| - Guest routes (no login required)
| - Authenticated routes (login required)
| - Admin-only routes (role-based access)
|
*/

// ============================================
// PUBLIC ROUTES (No login required)
// ============================================

// Welcome page (your existing HTML/UI)
Route::get('/', function () {
    return view('welcome'); // Your existing welcome.blade.php
})->name('welcome');

// ============================================
// GUEST ROUTES (Only non-logged-in users can access)
// ============================================
Route::middleware('guest')->group(function () {

    // Show login form
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // SECURITY: POST request for sensitive data (login)
    Route::post('/login', [AuthController::class, 'login']);

    // Show registration form
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    // SECURITY: POST request for sensitive data (registration)
    Route::post('/register', [AuthController::class, 'register']);

    // Bonus: Password reset routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ============================================
// AUTHENTICATED ROUTES (Login required)
// ============================================
Route::middleware('auth')->group(function () {

    // Dashboard (shows user list for admin, own profile for regular users)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========================================
    // CRUD OPERATIONS FOR USERS
    // ========================================

    // CREATE - Show create user form
    Route::get('/users/create', [DashboardController::class, 'create'])->name('users.create');

    // CREATE - Store new user (POST request for data submission)
    // SECURITY: CSRF protection automatically applied
    Route::post('/users', [DashboardController::class, 'store'])->name('users.store');

    // READ - Show single user (optional, could be used for profile view)
    Route::get('/users/{id}', [DashboardController::class, 'show'])->name('users.show');

    // UPDATE - Show edit form
    Route::get('/users/{id}/edit', [DashboardController::class, 'edit'])->name('users.edit');

    // UPDATE - Update user (PUT/PATCH request)
    Route::put('/users/{id}', [DashboardController::class, 'update'])->name('users.update');
    Route::patch('/users/{id}', [DashboardController::class, 'update'])->name('users.update.patch');

    // DELETE - Delete user with confirmation
    // SECURITY: DELETE method prevents accidental GET requests
    Route::delete('/users/{id}', [DashboardController::class, 'destroy'])->name('users.destroy');

    // Profile routes (users can edit their own profile)
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // SECURITY: Logout (POST request to prevent CSRF)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ============================================
// ADMIN-ONLY ROUTES (Bonus: Role-Based Access)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin dashboard with analytics
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

    // User management (admins only)
    Route::get('/users', [DashboardController::class, 'allUsers'])->name('admin.users');
    Route::delete('/users/{id}/force', [DashboardController::class, 'forceDelete'])->name('admin.users.force-delete');

    // System logs (optional)
    Route::get('/logs', [DashboardController::class, 'systemLogs'])->name('admin.logs');
});

// ============================================
// FALLBACK ROUTE (Handle 404 errors gracefully)
// ============================================
Route::fallback(function () {
    return view('errors.404');
});
