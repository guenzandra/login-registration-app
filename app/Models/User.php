<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * Only allow necessary fields to prevent mass assignment vulnerabilities
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'terms_accepted',
        'last_login_ip',
        'last_login_at',
        'login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Prevents sensitive data from being exposed in JSON responses
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'session_id',
    ];

    /**
     * The attributes that should be cast.
     * Ensures data types are correct and secure
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'terms_accepted' => 'boolean',
        'password' => 'hashed', // Laravel 10+ automatically hashes
        'login_attempts' => 'integer',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Security: Check if account is locked
     * Prevents brute force attacks by locking account after failed attempts
     */
    public function isLocked(): bool
    {
        if (!$this->locked_until) {
            return false;
        }

        return now()->lessThan($this->locked_until);
    }

    /**
     * Security: Increment failed login attempts
     * Locks account after 5 failed attempts for 15 minutes
     */
    public function incrementLoginAttempts(): void
    {
        $this->login_attempts++;

        // Lock account after 5 failed attempts
        if ($this->login_attempts >= 5) {
            $this->locked_until = now()->addMinutes(15);
        }

        $this->save();
    }

    /**
     * Security: Reset login attempts on successful login
     */
    public function resetLoginAttempts(): void
    {
        $this->login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Security: Update last login information
     * Helps track suspicious activities
     */
    public function recordLogin(string $ip): void
    {
        $this->last_login_at = now();
        $this->last_login_ip = $ip;
        $this->session_id = session()->getId();
        $this->save();

        // Log the activity
        ActivityLog::log($this->id, 'login', $ip, 'User logged in successfully');
    }

    /**
     * Security: Check if this session is the only active session
     * Prevents session hijacking
     */
    public function isCurrentSession(): bool
    {
        return $this->session_id === session()->getId();
    }

    /**
     * Security: Role-based access control
     * Returns true if user has the specified role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Security: Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Relationship for roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Relationship for activity logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
