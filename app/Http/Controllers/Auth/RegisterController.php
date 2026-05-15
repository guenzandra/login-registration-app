<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  /**
   * Security: Validate registration with multiple rules
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Only letters and spaces
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => [
        'required',
        'string',
        'min:8',           // Minimum 8 characters
        'confirmed',
        'regex:/[a-z]/',    // At least one lowercase letter
        'regex:/[A-Z]/',    // At least one uppercase letter
        'regex:/[0-9]/',    // At least one number
        'regex:/[@$!%*#?&]/' // At least one special character
      ],
      'terms_accepted' => ['required', 'boolean', 'accepted'],
    ], [
      'password.regex' => 'Password must contain at least one uppercase, one lowercase, one number, and one special character.',
      'terms_accepted.accepted' => 'You must accept the terms and conditions.'
    ]);
  }

  /**
   * Security: Create user with hashed password
   */
  public function register(Request $request)
  {
    // Security: CSRF token is automatically validated

    // Security: Validate input
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    // Security: Sanitize inputs to prevent XSS
    $name = htmlspecialchars(strip_tags($request->name));
    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);

    // Security: Create user with hashed password (Laravel automatically hashes)
    $user = User::create([
      'name' => $name,
      'email' => $email,
      'password' => $request->password,
      'terms_accepted' => true,
    ]);

    // Security: Assign default role
    $user->roles()->attach(Role::where('name', Role::ROLE_USER)->first());

    // Log the registration
    ActivityLog::log($user->id, 'user_registered', $request->ip(), 'New account created');

    // Optional: Send verification email
    // $user->sendEmailVerificationNotification();

    return redirect('/login')->with('success', 'Registration successful! Please login.');
  }
}
