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
  /**
   * INDEX - Ito yung pinaka-main page ng account management
   * Dito kinukuha yung statistics para ipakita sa dashboard
   */
  public function index()
  {
    // Kuha lahat ng total users sa database
    $totalAccounts = User::count();

    // Kuha yung mga users na nag-register sa nakalipas na 30 araw
    $newAccountsThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();

    // Kuha yung active sessions (mga naka-login ngayon)
    $activeSessions = $this->getActiveSessionsCount();

    // Ibalik yung view ng accounts kasama yung mga statistics
    return view('admin.accounts', compact('totalAccounts', 'newAccountsThisMonth', 'activeSessions'));
  }

  /**
   * GET USERS - Ito yung nagfa-fetch ng users para sa datatable
   * Ginagamit to ng AJAX para mag-load ng users kada page
   */
  public function getUsers(Request $request)
  {
    try {
      // Kunin yung mga filter galing sa request (search, role, status)
      $search = $request->input('search', '');      // Search keyword
      $role = $request->input('role', '');          // Role filter (admin, mod, user)
      $status = $request->input('status', '');      // Status filter (active, inactive, banned)
      $page = $request->input('page', 1);           // Current page number
      $perPage = $request->input('per_page', 5);    // Ilang users per page

      // Gawin yung query para kumuha ng users
      $query = User::query()
        ->search($search)      // Hanapin yung match sa name or email
        ->byRole($role)        // Filter by role
        ->byStatus($status);   // Filter by status

      // Bilangin total na users na match sa filters
      $total = $query->count();

      // Kunin yung users para sa current page (may pagination)
      $users = $query->orderBy('created_at', 'desc')  // Sort by latest
        ->skip(($page - 1) * $perPage)  // I-skip yung mga nasa previous pages
        ->take($perPage)                // Kunin lang yung limit per page
        ->get();

      // Ibalik sa JSON para magamit ng JavaScript
      return response()->json([
        'success' => true,
        'data' => $users,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage)  // Compute total pages
      ]);
    } catch (\Exception $e) {
      // Pag may error, ibalik yung error message
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch users: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * GET STATISTICS - Kunin lahat ng statistics para sa dashboard
   * Ginagamit to ng AJAX para mag-auto refresh yung numbers
   */
  public function getStatistics()
  {
    try {
      $stats = [
        'total_accounts' => User::count(),     // Lahat ng users
        'new_this_month' => User::where('created_at', '>=', now()->subDays(30))->count(), // Bago sa 30 days
        'active_sessions' => $this->getActiveSessionsCount(), // Active ngayon
        'admin_count' => User::where('role', 'admin')->count(),   // Bilang ng admin
        'mod_count' => User::where('role', 'mod')->count(),       // Bilang ng moderator
        'user_count' => User::where('role', 'user')->count(),     // Bilang ng regular users
        'active_users' => User::where('status', 'active')->count(),   // Active na accounts
        'inactive_users' => User::where('status', 'inactive')->count(), // Inactive accounts
        'banned_users' => User::where('status', 'banned')->count(),     // Banned accounts
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

  /**
   * GET ACTIVE SESSIONS COUNT - Private method para mabilang active sessions
   * Dalawang paraan: gamit sessions table or updated_at ng users
   */
  private function getActiveSessionsCount()
  {
    // Paraan 1: Gamit ang sessions table ng Laravel (mas accurate)
    if (Schema::hasTable('sessions')) {
      return DB::table('sessions')
        ->where('last_activity', '>=', now()->subMinutes(15)->timestamp) // Active sa last 15 mins
        ->count();
    }

    // Paraan 2: Backup kung walang sessions table
    // Bilangin yung mga users na na-update sa last 15 minutes
    return User::where('updated_at', '>=', now()->subMinutes(15))
      ->count();
  }

  /**
   * STORE - Gumawa ng bagong user
   * Ito yung tinatawag kapag nag-submit ng add user form
   */
  public function store(Request $request)
  {
    try {
      // I-validate muna yung mga input para siguradong tama
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',     // Kailangan meron, text lang, max 255 chars
        'last_name' => 'required|string|max:255',      // Kailangan meron, text lang, max 255 chars
        'email' => 'required|email|unique:users,email', // Kailangan unique email
        'password' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/', // Strong password
        'role' => 'required|in:admin,mod,user',        // Pwede lang admin, mod, or user
        'status' => 'required|in:active,inactive,banned', // Pwede lang active, inactive, or banned
      ]);

      // Pag may error sa validation, ibalik agad
      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // Gawin yung bagong user sa database
      $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // I-hash yung password bago i-save
        'role' => $request->role,
        'status' => $request->status,
      ]);

      // Success! Ibalik yung ginawang user
      return response()->json([
        'success' => true,
        'message' => 'User created successfully',
        'data' => $user
      ]);
    } catch (\Exception $e) {
      // Pag may error, sabihin kung ano
      return response()->json([
        'success' => false,
        'message' => 'Failed to create user: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * UPDATE - Baguhin ang existing user
   * Ginagamit to kapag nag-edit ng user account
   */
  public function update(Request $request, $id)
  {
    try {
      // Hanapin yung user gamit ang ID, pag wala mag-eerror
      $user = User::findOrFail($id);

      // I-validate yung mga bagong values
      $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id, // Pwede same email except sa current user
        'role' => 'required|in:admin,mod,user',
        'status' => 'required|in:active,inactive,banned',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // I-update yung user sa database
      $user->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'role' => $request->role,
        'status' => $request->status,
        // Hindi na ina-update ang password dito kasi separate form yun
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

  /**
   * DESTROY - Burahin ang isang user
   * Permanenteng tatanggalin ang user sa database
   */
  public function destroy($id)
  {
    try {
      // Hanapin yung user, pag wala mag-eerror
      $user = User::findOrFail($id);
      $user->delete(); // Burahin na

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

  /**
   * BULK DELETE - Burahin ang maraming users ng sabay-sabay
   * Ginagamit to kapag pumili ng multiple users at dinelete
   */
  public function bulkDelete(Request $request)
  {
    try {
      // I-validate kung may mga IDs na pinasa at umiiral ba sila
      $validator = Validator::make($request->all(), [
        'ids' => 'required|array',                    // Kailangan array ng IDs
        'ids.*' => 'exists:users,id',                // Lahat ng IDs dapat existing
      ]);

      if ($validator->fails()) {
        return response()->json([
          'success' => false,
          'errors' => $validator->errors()
        ], 422);
      }

      // Burahin lahat ng users na nasa array ng IDs
      User::whereIn('id', $request->ids)->delete();

      return response()->json([
        'success' => true,
        'message' => count($request->ids) . ' users deleted successfully' // Ilan ang nabura
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete users: ' . $e->getMessage()
      ], 500);
    }
  }
}
