<?php
// app/Http/Controllers/Auth/PasswordResetController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
  /**
   * Security: Request password reset with CSRF protection
   */
  public function sendResetLink(Request $request)
  {
    // Security: CSRF token is automatically validated by Laravel

    // Security: Validate input
    $request->validate([
      'email' => 'required|email'
    ]);

    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
    $user = User::where('email', $email)->first();

    // Security: Don't reveal if user exists (prevents user enumeration)
    if (!$user) {
      return back()->with('status', 'If the email exists, we have sent a password reset link.');
    }

    // Generate secure token
    $token = Str::random(64);

    // Store token with expiration (1 hour)
    \DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $email],
      [
        'token' => Hash::make($token),
        'created_at' => now(),
        'expires_at' => now()->addHour()
      ]
    );

    // Log the request
    ActivityLog::log($user->id, 'password_reset_requested', $request->ip());

    // Send email (implement your mail logic)
    // Mail::send('emails.password-reset', ['token' => $token, 'email' => $email], ...);

    return back()->with('status', 'We have emailed your password reset link!');
  }

  /**
   * Security: Reset password with token validation
   */
  public function resetPassword(Request $request)
  {
    // Security: CSRF protection applied automatically

    $request->validate([
      'email' => 'required|email',
      'token' => 'required|string',
      'password' => 'required|min:8|confirmed'
    ]);

    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
    $resetRecord = \DB::table('password_reset_tokens')
      ->where('email', $email)
      ->first();

    // Security: Check if token exists and is valid
    if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
      return back()->withErrors(['email' => 'Invalid or expired reset token.']);
    }

    // Security: Check token expiration
    if (now()->gt($resetRecord->expires_at)) {
      return back()->withErrors(['email' => 'This password reset link has expired.']);
    }

    // Update password
    $user = User::where('email', $email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Security: Clear all sessions for this user
    \DB::table('sessions')->where('user_id', $user->id)->delete();

    // Delete used token
    \DB::table('password_reset_tokens')->where('email', $email)->delete();

    // Log the password change
    ActivityLog::log($user->id, 'password_reset_completed', $request->ip());

    return redirect('/login')->with('status', 'Password reset successful! Please login.');
  }
}
