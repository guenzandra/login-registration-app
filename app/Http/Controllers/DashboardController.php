<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
  /**
   * SECURITY: Middleware ensures only logged-in users can access
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * READ: Display dashboard with users list
   * SECURITY: Only admins can see all users
   */
  public function index()
  {
    // Check if admin (RBAC - Bonus)
    if (auth()->user()->role === 'admin') {
      // Admin sees all users
      $users = User::paginate(10);
    } else {
      // Regular users only see themselves
      $users = User::where('id', auth()->id())->paginate(10);
    }

    return view('dashboard', ['users' => $users]);
  }

  /**
   * CREATE: Show create user form
   * SECURITY: Only admins can create users
   */
  public function create()
  {
    // RBAC check
    if (auth()->user()->role !== 'admin') {
      return redirect('/dashboard')->with('error', 'Unauthorized access');
    }

    return view('users.create');
  }

  /**
   * CREATE: Store new user
   * SECURITY: Prepared statements (Laravel Eloquent)
   * SECURITY: XSS prevention via sanitization
   */
  public function store(Request $request)
  {
    // RBAC check
    if (auth()->user()->role !== 'admin') {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Server-side validation
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|min:2|max:255',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8',
      'role' => 'in:user,admin'
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    // SECURITY: Sanitize inputs
    $name = htmlspecialchars(strip_tags($request->name));
    $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);

    // SECURITY: Create user (password auto-hashed)
    $user = User::create([
      'name' => $name,
      'email' => $email,
      'password' => $request->password,
      'role' => $request->role ?? 'user',
    ]);

    return response()->json(['message' => 'User created successfully', 'user' => $user]);
  }

  /**
   * UPDATE: Show edit form
   */
  public function edit($id)
  {
    // RBAC: Users can only edit their own profile
    if (auth()->user()->role !== 'admin' && auth()->id() != $id) {
      return redirect('/dashboard')->with('error', 'Unauthorized access');
    }

    $user = User::findOrFail($id);
    return view('users.edit', ['user' => $user]);
  }

  /**
   * UPDATE: Update user information
   * SECURITY: Prepared statements
   * SECURITY: XSS prevention
   */
  public function update(Request $request, $id)
  {
    // RBAC check
    if (auth()->user()->role !== 'admin' && auth()->id() != $id) {
      return response()->json(['error' => 'Unauthorized'], 403);
    }

    $user = User::findOrFail($id);

    // Server-side validation
    $validator = Validator::make($request->all(), [
      'name' => 'sometimes|string|min:2|max:255',
      'email' => 'sometimes|email|unique:users,email,' . $id,
      'password' => 'sometimes|min:8',
      'role' => 'sometimes|in:user,admin'
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    // SECURITY: Sanitize inputs
    if ($request->has('name')) {
      $user->name = htmlspecialchars(strip_tags($request->name));
    }

    if ($request->has('email')) {
      $user->email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
    }

    // SECURITY: Only admins can change roles
    if ($request->has('role') && auth()->user()->role === 'admin') {
      $user->role = $request->role;
    }

    // SECURITY: Hash password if changed
    if ($request->has('password')) {
      $user->password = $request->password; // Auto-hashed by Laravel
    }

    $user->save();

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
  }

  /**
   * DELETE: Remove user
   * SECURITY: Confirmation prompt handled by frontend
   * SECURITY: Prevent self-deletion for non-admins
   */
  public function destroy($id)
  {
    // RBAC: Only admins can delete users
    if (auth()->user()->role !== 'admin') {
      return response()->json(['error' => 'Unauthorized. Only admins can delete users.'], 403);
    }

    // SECURITY: Prevent admin from deleting themselves
    if (auth()->id() == $id) {
      return response()->json(['error' => 'You cannot delete your own account.'], 400);
    }

    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
  }
}
