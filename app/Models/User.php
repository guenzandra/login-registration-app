<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * SECURITY: Only these fields can be mass assigned
     * Prevents mass assignment vulnerabilities
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /*SECURITY: Hide sensitive data when converting to JSON/array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* SECURITY: Auto-cast data types*/
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', //auto-hashes using bcrypt (password_hash equivalent)
    ];
}
