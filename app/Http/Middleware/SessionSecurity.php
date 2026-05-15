<?php
// app/Http/Middleware/SessionSecurity.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SessionSecurity
{
  /**
   * SECURITY: Additional session validation
   */
  public function handle(Request $request, Closure $next)
  {
    if (Auth::check()) {
      $user = Auth::user();

      // SECURITY: Check if session matches stored session ID
      if ($user->session_id && $user->session_id !== session()->getId()) {
        // User logged in elsewhere - optional: logout current session
        Auth::logout();
        $request->session()->invalidate();

        return redirect('/login')->withErrors([
          'email' => 'You have been logged out because you logged in on another device.'
        ]);
      }

      // SECURITY: Check IP consistency (optional, may cause issues with mobile networks)
      if ($user->last_login_ip && $user->last_login_ip !== $request->ip()) {
        // Log suspicious activity but don't force logout
        \App\Models\ActivityLog::log(
          $user->id,
          'suspicious_ip',
          $request->ip(),
          "IP changed from {$user->last_login_ip}"
        );
      }
    }

    // SECURITY: Regenerate session ID periodically
    if (!session()->has('session_regenerated_at')) {
      session()->put('session_regenerated_at', time());
    }

    // Regenerate session ID every 30 minutes
    if (time() - session()->get('session_regenerated_at') > 1800) {
      $request->session()->regenerate();
      session()->put('session_regenerated_at', time());
    }

    return $next($request);
  }
}
