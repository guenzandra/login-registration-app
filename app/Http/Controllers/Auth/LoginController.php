<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
  /**
   * Security: Maximum login attempts before rate limiting
   */
  protected $maxAttempts = 5;

  /**
   * Security: Decay minutes for rate limiter
   */
  protected $decayMinutes = 15;

  /**
   * Handle login request with comprehensive security
   */
  public function login(Request $request)
  {
    // Security: Validate input (prevents SQL injection & XSS)
    $request->validate([
      'email' => 'required|email|string|max:255',
      'password' => 'required|string|min:6',
    ]);

    // Security: Sanitize email (remove any potential XSS)
    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
    $ip = $request->ip();

    // Security: Rate limiting - Prevent brute force attacks
    $key = 'login_attempts_' . $ip;
    if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
      $seconds = RateLimiter::availableIn($key);
      return back()->withErrors([
        'email' => "Too many login attempts. Please try again in {$seconds} seconds."
      ]);
    }

    // Security: Find user
    $user = User::where('email', $email)->first();

    // Security: Check if account is locked
    if ($user && $user->isLocked()) {
      $lockedUntil = $user->locked_until->diffForHumans();
      ActivityLog::log($user->id, 'locked_login_attempt', $ip, "Account locked until {$lockedUntil}");
      return back()->withErrors([
        'email' => "Your account is temporarily locked. Please try again {$lockedUntil}."
      ]);
    }

    // Security: Attempt authentication
    if ($user && Hash::check($request->password, $user->password)) {
      // Successful login - reset security measures
      RateLimiter::clear($key);
      $user->resetLoginAttempts();

      // Regenerate session ID to prevent session fixation
      $request->session()->regenerate();

      // Log the login
      $user->recordLogin($ip);

      // Login the user
      Auth::login($user);

      // Redirect based on role (RBAC)
      if ($user->isAdmin()) {
        return redirect()->intended('/admin/dashboard');
      }

      return redirect()->intended('/dashboard');
    }

    // Security: Failed login - increment counters
    RateLimiter::hit($key, $this->decayMinutes * 60);

    if ($user) {
      $user->incrementLoginAttempts();
      ActivityLog::log($user->id, 'failed_login', $ip, 'Invalid password');
    } else {
      ActivityLog::log(null, 'failed_login_no_user', $ip, "Email: {$email} not found");
    }

    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
  }

  /**
   * Security: Logout with session cleanup
   */
  public function logout(Request $request)
  {
    $user = Auth::user();

    if ($user) {
      // Log the logout activity
      ActivityLog::log($user->id, 'logout', $request->ip(), 'User logged out');

      // Clear session ID from user record
      $user->session_id = null;
      $user->save();
    }

    // Security: Clear all session data
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }
}
