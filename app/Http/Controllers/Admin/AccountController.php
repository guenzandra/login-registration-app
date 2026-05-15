<?php
// app/Http/Controllers/Admin/AccountController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountController extends Controller
{
  public function index()
  {
    // Get statistics
    $totalAccounts = User::count();
    $newAccountsThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();

    // Get active sessions (using Laravel's session table if available)
    $activeSessions = $this->getActiveSessionsCount();

    return view('admin.accounts', compact('totalAccounts', 'newAccountsThisMonth', 'activeSessions'));
  }

  public function getUsers(Request $request)
  {
    try {
      $search = $request->input('search', '');
      $role = $request->input('role', '');
      $status = $request->input('status', '');
      $page = $request->input('page', 1);
      $perPage = $request->input('per_page', 5);

      $query = User::query()
        ->search($search)
        ->byRole($role)
        ->byStatus($status);

      $total = $query->count();
      $users = $query->orderBy('created_at', 'desc')
        ->skip(($page - 1) * $perPage)
        ->take($perPage)
        ->get();

      return response()->json([
        'success' => true,
        'data' => $users,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage)
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch users: ' . $e->getMessage()
      ], 500);
    }
  }

  public function getStatistics()
  {
    try {
      $stats = [
        'total_accounts' => User::count(),
        'new_this_month' => User::where('created_at', '>=', now()->subDays(30))->count(),
        'active_sessions' => $this->getActiveSessionsCount(),
        'admin_count' => User::where('role', 'admin')->count(),
        'mod_count' => User::where('role', 'mod')->count(),
        'user_count' => User::where('role', 'user')->count(),
        'active_users' => User::where('status', 'active')->count(),
        'inactive_users' => User::where('status', 'inactive')->count(),
        'banned_users' => User::where('status', 'banned')->count(),
      ];

      return response()->json([
        'success' => true,
        'data' => $stats
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch statistics: ' . $e->getMessage()
      ], 500);
    }
  }

  private function getActiveSessionsCount()
  {
    // Method 1: Using Laravel's session table (recommended)
    if (Schema::hasTable('sessions')) {
      return DB::table('sessions')
        ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
        ->count();
    }

    // Method 2: Fallback - count users who logged in within last 15 minutes
    return User::where('updated_at', '>=', now()->subMinutes(15))
      ->count();
  }

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/',
        'role' => 'required|in:admin,mod,user',
        'status' => 'required|in:active,inactive,banned',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
      ]);

      return response()->json([
        'success' => true,
        'message' => 'User created successfully',
        'data' => $user
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to create user: ' . $e->getMessage()
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $user = User::findOrFail($id);

      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:admin,mod,user',
        'status' => 'required|in:active,inactive,banned',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'role' => $request->role,
        'status' => $request->status,
      ]);

      return response()->json([
        'success' => true,
        'message' => 'User updated successfully',
        'data' => $user
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update user: ' . $e->getMessage()
      ], 500);
    }
  }

  public function destroy($id)
  {
    try {
      $user = User::findOrFail($id);
      $user->delete();

      return response()->json([
        'success' => true,
        'message' => 'User deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete user: ' . $e->getMessage()
      ], 500);
    }
  }

  public function bulkDelete(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'ids' => 'required|array',
        'ids.*' => 'exists:users,id',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      User::whereIn('id', $request->ids)->delete();

      return response()->json([
        'success' => true,
        'message' => count($request->ids) . ' users deleted successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete users: ' . $e->getMessage()
      ], 500);
    }
  }
}
