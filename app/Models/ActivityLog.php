<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
  protected $fillable = [
    'user_id',
    'action',
    'ip_address',
    'user_agent',
    'details'
  ];

  /**
   * Security: Log user activities for audit trail
   */
  public static function log($userId, $action, $ip = null, $details = null)
  {
    return self::create([
      'user_id' => $userId,
      'action' => $action,
      'ip_address' => $ip ?? Request::ip(),
      'user_agent' => Request::userAgent(),
      'details' => $details,
    ]);
  }

  /**
   * User relationship
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
