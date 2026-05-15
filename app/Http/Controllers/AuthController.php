<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
  public function showWelcome()
  {
    return view('welcome');
  }

  public function register(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/',
        'terms' => 'accepted',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // Split name into first and last name
      $nameParts = explode(' ', $request->name, 2);
      $firstName = $nameParts[0];
      $lastName = $nameParts[1] ?? '';

      $user = User::create([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
        'status' => 'active',
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Account created successfully!',
        'user' => $user->only(['id', 'first_name', 'last_name', 'email'])
      ]);
    } catch (\Exception $e) {
      Log::error('Registration error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Registration failed: ' . $e->getMessage()
      ], 500);
    }
  }

  public function login(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid email or password.'
        ], 401);
      }

      if ($user->status !== 'active') {
        return response()->json([
          'success' => false,
          'message' => 'Your account is ' . $user->status . '. Please contact support.'
        ], 403);
      }

      auth()->login($user);
      session()->regenerate();

      return response()->json([
        'success' => true,
        'message' => 'Login successful!',
        'redirect' => route('admin.dashboard'),
        'user' => $user->only(['id', 'first_name', 'last_name', 'email', 'role'])
      ]);
    } catch (\Exception $e) {
      Log::error('Login error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Login failed: ' . $e->getMessage()
      ], 500);
    }
  }

  public function sendResetCode(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'Email not found.'
        ], 404);
      }

      $resetCode = $user->generateResetCode();

      // Send email (you can use Mail facade or log for testing)
      try {
        Mail::send([], [], function ($message) use ($user, $resetCode) {
          $message->to($user->email)
            ->subject('Password Reset Code - GuenZandra')
            ->html("<h2>Password Reset Request</h2>
                                   <p>Hello {$user->first_name},</p>
                                   <p>You requested to reset your password. Here is your verification code:</p>
                                   <h1 style='font-size: 32px; letter-spacing: 5px;'>{$resetCode}</h1>
                                   <p>This code will expire in 15 minutes.</p>
                                   <p>If you didn't request this, please ignore this email.</p>");
        });
      } catch (\Exception $e) {
        // For testing without mail server, log the code
        Log::info('Password reset code for ' . $user->email . ': ' . $resetCode);

        // You can still return success but with a note
        return response()->json([
          'success' => true,
          'message' => 'Reset code generated. (For testing: ' . $resetCode . ')',
          'debug_code' => $resetCode // Remove in production
        ]);
      }

      return response()->json([
        'success' => true,
        'message' => 'Reset code sent to your email!'
      ]);
    } catch (\Exception $e) {
      Log::error('Send reset code error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to send reset code: ' . $e->getMessage()
      ], 500);
    }
  }

  public function verifyResetCode(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string|size:6',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      if (!$user || !$user->verifyResetCode($request->code)) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid or expired reset code.'
        ], 401);
      }

      return response()->json([
        'success' => true,
        'message' => 'Code verified successfully!'
      ]);
    } catch (\Exception $e) {
      Log::error('Verify reset code error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Verification failed: ' . $e->getMessage()
      ], 500);
    }
  }

  public function resetPassword(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string|size:6',
        'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/',
        'password_confirmation' => 'required|same:password',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      if (!$user || !$user->verifyResetCode($request->code)) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid or expired reset code.'
        ], 401);
      }

      $user->password = Hash::make($request->password);
      $user->clearResetCode();
      $user->save();

      return response()->json([
        'success' => true,
        'message' => 'Password reset successfully! You can now login.'
      ]);
    } catch (\Exception $e) {
      Log::error('Reset password error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Password reset failed: ' . $e->getMessage()
      ], 500);
    }
  }

  public function logout(Request $request)
  {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Logged out successfully'
      ]);
    }

    return redirect('/');
  }

  public function getStatistics()
  {
    try {
      $stats = [
        'total_users' => User::count(),
        'new_this_month' => User::where('created_at', '>=', now()->subDays(30))->count(),
        'active_users' => User::where('status', 'active')->count(),
      ];

      return response()->json([
        'success' => true,
        'data' => $stats
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch statistics'
      ], 500);
    }
  }
}
