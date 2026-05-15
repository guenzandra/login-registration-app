<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  /**
   * SECURITY: Show login form
   */
  public function showLogin()
  {
    return view('login'); // Your existing HTML UI
  }

  /**
   * SECURITY: Handle login request
   * Uses POST method for sensitive data
   * Server-side validation
   * Prepared statements (Laravel Eloquent does this automatically)
   */
  public function login(Request $request)
  {
    // SECURITY: Server-side validation
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|max:255',
      'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    // SECURITY: Sanitize input to prevent XSS
    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
    $password = $request->password;

    // SECURITY: Attempt login (Laravel uses password_verify automatically)
    $credentials = ['email' => $email, 'password' => $password];

    if (Auth::attempt($credentials, $request->remember)) {
      // SECURITY: Regenerate session ID to prevent session fixation
      $request->session()->regenerate();

      // Redirect to dashboard
      return redirect()->intended('/dashboard');
    }

    // User-friendly error message
    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
  }

  /**
   * SECURITY: Handle registration
   */
  public function register(Request $request)
  {
    // SECURITY: Server-side validation
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|min:2|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:8|confirmed',
      'terms_accepted' => 'accepted',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    // SECURITY: Sanitize inputs to prevent XSS
    $name = htmlspecialchars(strip_tags($request->name));
    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);

    // SECURITY: Create user with hashed password (password_hash equivalent)
    // Laravel's 'password' mutator automatically hashes using bcrypt
    $user = User::create([
      'name' => $name,
      'email' => $email,
      'password' => $request->password, // Auto-hashed by Laravel
      'role' => 'user', // Default role
    ]);

    // Auto-login after registration
    Auth::login($user);

    // Regenerate session
    $request->session()->regenerate();

    return redirect('/dashboard')->with('success', 'Registration successful! Welcome!');
  }

  /**
   * SECURITY: Handle logout
   */
  public function logout(Request $request)
  {
    // SECURITY: Clear session
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
  }
}
