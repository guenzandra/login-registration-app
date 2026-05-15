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
  /**
   * INDEX - Ito yung pinaka-main page ng admin dashboard
   * Dito kinukuha yung mga statistics na ipapakita sa cards
   */
  public function index()
  {
    // Kuha lahat ng total users sa database
    $totalAccounts = User::count();

    // Kuha yung mga users na nag-register sa nakalipas na 30 araw
    $newAccountsThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();

    // Kuha yung active sessions (mga naka-login ngayon)
    $activeSessions = $this->getActiveSessionsCount();

    // Ibalik yung dashboard view kasama yung mga statistics
    return view('admin.dashboard', compact('totalAccounts', 'newAccountsThisMonth', 'activeSessions'));
  }

  /**
   * GET STATISTICS - Kunin lahat ng statistics para sa dashboard
   * Ginagamit to ng AJAX para mag-auto refresh yung mga numbers
   * Hindi na kailangan i-reload ang buong page
   */
  public function getStatistics()
  {
    try {
      $stats = [
        'total_accounts' => User::count(),     // Lahat ng users kahit ano pa role or status
        'new_this_month' => User::where('created_at', '>=', now()->subDays(30))->count(), // Bago sa 30 araw
        'active_sessions' => $this->getActiveSessionsCount(), // Mga active ngayon
        'admin_count' => User::where('role', 'admin')->count(),   // Bilang ng admin
        'mod_count' => User::where('role', 'mod')->count(),       // Bilang ng moderator
        'user_count' => User::where('role', 'user')->count(),     // Bilang ng regular users
        'active_users' => User::where('status', 'active')->count(),   // Active na accounts
        'inactive_users' => User::where('status', 'inactive')->count(), // Inactive accounts (disabled)
        'banned_users' => User::where('status', 'banned')->count(),     // Banned accounts (violators)
      ];

      // Ibalik sa JSON format para magamit ng JavaScript
      return response()->json([
        'success' => true,
        'data' => $stats
      ]);
    } catch (\Exception $e) {
      // Pag may error, sabihin kung ano ang problema
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch statistics: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * GET RECENT ACTIVITY - Kunin yung mga latest na ginawa sa system
   * Dito makikita yung table ng recent activities sa dashboard
   */
  public function getRecentActivity()
  {
    try {
      // Kunin ang 10 pinakabagong users (base sa registration date)
      $recentUsers = User::orderBy('created_at', 'desc')  // Pinakabago muna
        ->take(10)  // 10 lang muna
        ->get()     // Kunin na
        ->map(function ($user) {  // I-format yung data para sa frontend
          return [
            'activity' => 'New user registered',  // Description ng activity
            'user' => $user->first_name . ' ' . $user->last_name,  // Buong pangalan
            'status' => $user->status,  // Status ng user (active/inactive/banned)
            'created_at' => $user->created_at->diffForHumans()  // "2 hours ago" format
          ];
        });

      // Pwede kang magdagdag ng ibang activities dito tulad ng:
      // - User logins
      // - Profile updates
      // - Password changes
      // - etc.

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

  /**
   * EXPORT REPORT - Gumawa ng CSV file ng lahat ng users
   * Pwede i-download para ma-open sa Excel
   * NOTE: Hindi na 'to ginagamit sa bagong version (inalis na yung export button)
   * Pero nandito pa rin kung gusto mong ibalik
   *///di ko na to tinuloy nagkakaprobelm sa side ko and di naman siya need and also super late na pasa ko
  public function exportReport()
  {
    try {
      // Kunin lahat ng users
      $users = User::all();

      // Pangalan ng file na may timestamp para unique
      $filename = 'users_report_' . date('Y-m-d_His') . '.csv';
      $path = storage_path('app/public/' . $filename);

      // Gumawa ng folder kung wala pa
      if (!file_exists(storage_path('app/public'))) {
        mkdir(storage_path('app/public'), 0755, true);
      }

      // Buksan ang file para isulat
      $file = fopen($path, 'w');

      // Add UTF-8 BOM - para mabasa ng Excel ng tama ang special characters (ñ, é, etc.)
      fputs($file, "\xEF\xBB\xBF");

      // Ilagay ang mga column headers (pangalan ng columns)
      fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Role', 'Status', 'Created At', 'Updated At']);

      // Ilagay ang data ng bawat user
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

      // Iclose ang file
      fclose($file);

      // Ibabalik ang download link
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

  /**
   * GET ACTIVE SESSIONS COUNT - Pribadong method para mabilang ang active sessions
   * Dalawang paraan: gamit ang sessions table or updated_at ng users
   */
  private function getActiveSessionsCount()
  {
    // Option 1: Gamit ang sessions table ng Laravel (mas accurate to)
    // Kailangan muna i-check kung may sessions table
    if (Schema::hasTable('sessions')) {
      return DB::table('sessions')
        ->where('last_activity', '>=', now()->subMinutes(15)->timestamp) // Active sa loob ng 15 minutes
        ->count();
    }

    // Option 2: Backup kung walang sessions table
    // Bilangin yung mga users na na-update sa loob ng 15 minutes
    return User::where('updated_at', '>=', now()->subMinutes(15))->count();
  }
}
