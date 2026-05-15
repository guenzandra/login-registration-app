<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $fillable = ['name', 'display_name', 'description'];

  /**
   * Security: Define role constants
   */
  const ROLE_ADMIN = 'admin';
  const ROLE_USER = 'user';
  const ROLE_MODERATOR = 'moderator';

  /**
   * Users relationship
   */
  public function users()
  {
    return $this->belongsToMany(User::class);
  }
}
