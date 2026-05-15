<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
  public function index()
  {
    $totalAccounts = User::count();
    $newAccountsThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();
    $activeSessions = $this->getActiveSessionsCount();

    return view('admin.dashboard', compact('totalAccounts', 'newAccountsThisMonth', 'activeSessions'));
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

  public function getRecentActivity()
  {
    try {
      // Get recent user registrations
      $recentUsers = User::orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function ($user) {
          return [
            'activity' => 'New user registered',
            'user' => $user->first_name . ' ' . $user->last_name,
            'status' => $user->status,
            'created_at' => $user->created_at->diffForHumans()
          ];
        });

      // You can add more activity types here (e.g., logins, updates)
      // For now, return user registrations

      return response()->json([
        'success' => true,
        'data' => $recentUsers
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch activity: ' . $e->getMessage()
      ], 500);
    }
  }

  public function exportReport()
  {
    try {
      // Generate CSV report
      $users = User::all();
      $filename = 'users_report_' . date('Y-m-d_His') . '.csv';
      $path = storage_path('app/public/' . $filename);

      // Create directory if not exists
      if (!file_exists(storage_path('app/public'))) {
        mkdir(storage_path('app/public'), 0755, true);
      }

      $file = fopen($path, 'w');

      // Add UTF-8 BOM for Excel compatibility
      fputs($file, "\xEF\xBB\xBF");

      // Add headers
      fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Role', 'Status', 'Created At', 'Updated At']);

      // Add data
      foreach ($users as $user) {
        fputcsv($file, [
          $user->id,
          $user->first_name,
          $user->last_name,
          $user->email,
          $user->role,
          $user->status,
          $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : 'N/A',
          $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : 'N/A'
        ]);
      }

      fclose($file);

      return response()->json([
        'success' => true,
        'download_url' => asset('storage/' . $filename)
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to generate report: ' . $e->getMessage()
      ], 500);
    }
  }

  private function getActiveSessionsCount()
  {
    // Check if sessions table exists
    if (Schema::hasTable('sessions')) {
      return DB::table('sessions')
        ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
        ->count();
    }

    // Fallback: count users who were updated in the last 15 minutes
    return User::where('updated_at', '>=', now()->subMinutes(15))->count();
  }
}
