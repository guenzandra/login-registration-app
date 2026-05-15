<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'status',
        'reset_code',
        'reset_code_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_code'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reset_code_expires_at' => 'datetime',
    ];

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getJoinedAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'N/A';
    }

    // Password Reset Methods
    public function generateResetCode()
    {
        // Generate a random 6-digit code
        $this->reset_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->reset_code_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->reset_code;
    }

    public function verifyResetCode($code)
    {
        return $this->reset_code === $code && $this->reset_code_expires_at && $this->reset_code_expires_at > now();
    }

    public function clearResetCode()
    {
        $this->reset_code = null;
        $this->reset_code_expires_at = null;
        $this->save();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        if ($role) {
            return $query->where('role', $role);
        }
        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
