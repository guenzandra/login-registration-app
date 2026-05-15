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
  /**
   * SHOW WELCOME - Ipakita ang welcome/login page
   * Ito yung unang page na makikita ng user
   */
  public function showWelcome()
  {
    return view('welcome');
  }

  /**
   * REGISTER - Gumawa ng bagong user account
   * Mga hakbang: 1) Validate input, 2) Split name, 3) Create user, 4) Return response
   */
  public function register(Request $request)
  {
    try {
      // I-validate muna lahat ng input bago gumawa ng account
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:2|max:255',     // Kailangan may pangalan, 2 chars man lang
        'email' => 'required|email|unique:users,email', // Email dapat unique, wala pang existing
        'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/', // Strong password
        'terms' => 'accepted', // Dapat pumayag sa terms and conditions
      ]);

      // Pag may error sa validation, ibalik agad
      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // Paghiwalayin ang first name at last name (kunwari "Juan Dela Cruz" -> "Juan", "Dela Cruz")
      $nameParts = explode(' ', $request->name, 2);
      $firstName = $nameParts[0];           // Unang word
      $lastName = $nameParts[1] ?? '';      // Rest ng name, kung wala edi blank

      // Gumawa ng bagong user sa database
      $user = User::create([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $request->email,
        'password' => Hash::make($request->password), // I-hash ang password bago i-save
        'role' => 'user',     // Default role ay regular user
        'status' => 'active', // Active agad ang account
      ]);

      // Success! Ibalik ang ginawang user (except ang password at ibang sensitive data)
      return response()->json([
        'success' => true,
        'message' => 'Account created successfully!',
        'user' => $user->only(['id', 'first_name', 'last_name', 'email'])
      ]);
    } catch (\Exception $e) {
      // I-log ang error para makita sa storage/logs/laravel.log
      Log::error('Registration error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Registration failed: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * LOGIN - Mag-sign in ang existing user
   * Mga hakbang: 1) Validate, 2) Hanapin ang user, 3) Check password, 4) Login session
   */
  public function login(Request $request)
  {
    try {
      // I-validate ang email at password
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',           // Kailangan may email at valid format
        'password' => 'required|string|min:6', // May password, di pwedeng empty
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // Hanapin ang user gamit ang email
      $user = User::where('email', $request->email)->first();

      // Check kung may user at tama ang password
      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid email or password.'
        ], 401);
      }

      // Check kung active ang account
      if ($user->status !== 'active') {
        return response()->json([
          'success' => false,
          'message' => 'Your account is ' . $user->status . '. Please contact support.'
        ], 403);
      }

      // I-login ang user (gumawa ng session)
      auth()->login($user);
      session()->regenerate(); // Para secure, i-regenerate ang session ID

      // Success! Ibalik ang redirect URL at user info
      return response()->json([
        'success' => true,
        'message' => 'Login successful!',
        'redirect' => route('admin.dashboard'), // Diretso sa admin dashboard
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

  /**
   * SEND RESET CODE - Magpadala ng 6-digit code sa email ng user
   * Ito yung "Forgot Password" feature
   */
  public function sendResetCode(Request $request)
  {
    try {
      // I-validate kung may email at existing sa database
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email', // exists = dapat meron sa users table
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // Hanapin ang user
      $user = User::where('email', $request->email)->first();

      if (!$user) {
        return response()->json([
          'success' => false,
          'message' => 'Email not found.'
        ], 404);
      }

      // Gumawa ng random 6-digit code at i-save sa database
      $resetCode = $user->generateResetCode();

      // Subukan magpadala ng email (try-catch para kung walang mail server)
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
        // Pag walang mail server (like sa localhost), i-log na lang ang code
        Log::info('Password reset code for ' . $user->email . ': ' . $resetCode);

        // Ibalik pa rin ang success kasama ang code (for testing only)
        return response()->json([
          'success' => true,
          'message' => 'Reset code generated. (For testing: ' . $resetCode . ')',
          'debug_code' => $resetCode // REMOVE THIS IN PRODUCTION!
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

  /**
   * VERIFY RESET CODE - I-check kung tama ang code na inenter ng user
   * Bago payagan mag-reset ng password, dapat ma-verify muna ang code
   */
  public function verifyResetCode(Request $request)
  {
    try {
      // I-validate ang email at code (code dapat 6 digits)
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string|size:6', // Exactly 6 characters
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      // Verify kung tama ang code at hindi pa expired (15 minutes)
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

  /**
   * RESET PASSWORD - Baguhin ang password gamit ang verified code
   * Pag na-verify na ang code, pwede na mag-set ng bagong password
   */
  public function resetPassword(Request $request)
  {
    try {
      // I-validate lahat ng input
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string|size:6',
        'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/', // Strong password ulit
        'password_confirmation' => 'required|same:password', // Dapat match sa password
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::where('email', $request->email)->first();

      // I-verify ulit ang code bago mag-reset
      if (!$user || !$user->verifyResetCode($request->code)) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid or expired reset code.'
        ], 401);
      }

      // I-update ang password
      $user->password = Hash::make($request->password);
      $user->clearResetCode(); // Burahin na ang reset code (expired na)
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

  /**
   * LOGOUT - Mag-sign out sa system
   * Burahin ang session at i-redirect sa welcome page
   */
  public function logout(Request $request)
  {
    // I-logout ang user (burahin ang session)
    auth()->logout();
    $request->session()->invalidate();    // Sirain ang current session
    $request->session()->regenerateToken(); // Gumawa ng bagong CSRF token

    // Kung AJAX request, mag-respond ng JSON
    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Logged out successfully'
      ]);
    }

    // Kung normal request, i-redirect sa home page
    return redirect('/');
  }

  /**
   * GET STATISTICS - Kunin ang statistics para sa welcome page
   * Ipapakita sa left panel ng welcome page
   */
  public function getStatistics()
  {
    try {
      $stats = [
        'total_users' => User::count(),     // Lahat ng users
        'new_this_month' => User::where('created_at', '>=', now()->subDays(30))->count(), // Bago sa 30 araw
        'active_users' => User::where('status', 'active')->count(), // Active na users
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
