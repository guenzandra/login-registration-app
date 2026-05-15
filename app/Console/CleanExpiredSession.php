<?php
// app/Console/Commands/CleanExpiredSessions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanExpiredSessions extends Command
{
  protected $signature = 'session:clean';
  protected $description = 'Clean expired sessions from database';

  /**
   * SECURITY: Remove expired sessions regularly
   * Prevents session table from growing too large
   * Reduces risk of session replay attacks
   */
  public function handle()
  {
    $lifetime = config('session.lifetime');
    $expired = Carbon::now()->subMinutes($lifetime)->timestamp;

    $deleted = DB::table('sessions')
      ->where('last_activity', '<', $expired)
      ->delete();

    $this->info("Cleaned {$deleted} expired sessions.");

    // SECURITY: Also clean expired password reset tokens
    $resetTokensDeleted = DB::table('password_reset_tokens')
      ->where('expires_at', '<', now())
      ->delete();

    $this->info("Cleaned {$resetTokensDeleted} expired password reset tokens.");
  }
}
