<?php
// app/Http/Controllers/Admin/AccountController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
  public function index()
  {
    return view('admin.accounts');
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

  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
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

  public function updateStatus(Request $request, $id)
  {
    try {
      $user = User::findOrFail($id);

      $validator = Validator::make($request->all(), [
        'status' => 'required|in:active,inactive,banned',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      $user->update(['status' => $request->status]);

      return response()->json([
        'success' => true,
        'message' => 'User status updated successfully',
        'data' => $user
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update status: ' . $e->getMessage()
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
