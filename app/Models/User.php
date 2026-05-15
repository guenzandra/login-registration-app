<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // So like, 'use' dito is pang-traits sa PHP — parang ini-import ko yung mga reusable na features
    // 'HasFactory' pang-create ng dummy data sa testing, 'Notifiable' naman pang-send ng notifications like email
    use HasFactory, Notifiable;

    // 'protected $table' — ini-specify ko kung anong name ng table sa database 'to
    // Kasi default kasi 'users' naman, pero I like being explicit para walang confusion, gets?
    protected $table = 'users';

    // 'fillable' = mga columns na puwedeng i-mass assign (like pag gumamit ng create() or update())
    // Para iwas sa mass assignment vulnerability, so dito ko lang nilalagay yung safe i-save
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

    // 'hidden' — itong mga attributes na 'to hindi lalabas when mag-return tayo ng model as JSON/array
    // Para i-expose yung sensitive info like password, remember_token, etc.
    protected $hidden = [
        'password',
        'remember_token',
        'reset_code'
    ];

    // 'casts' — ginagawa kong specific data type yung attributes automatically
    // Like 'datetime' para maging Carbon object sila, so ang dali mag-manipulate ng dates
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reset_code_expires_at' => 'datetime',
    ];

    // Accessors — 'get{Something}Attribute' naming convention para mag-create ng virtual attribute
    // So 'getFullNameAttribute' means puwede ko nang tawaging '$user->full_name'
    // Galing diba? No need na mag-concatenate lagi
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Another accessor — for 'joined' attribute
    // Ginamitan ko ng 'format' method kasi Carbon object na siya due to casts above
    public function getJoinedAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'N/A';
    }

    // Password Reset Methods
    // Ito random 6-digit code for password reset, stored sa database
    public function generateResetCode()
    {
        // 'str_pad' para kahit maging 123 lang, magiging '000123' — consistent na 6 digits siya
        // 'random_int' for cryptographically secure random numbers
        $this->reset_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        // 'now()->addMinutes(15)' — expiration in 15 minutes para secure
        $this->reset_code_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->reset_code;
    }

    // Verify kung match yung code at hindi pa expired
    public function verifyResetCode($code)
    {
        return $this->reset_code === $code && $this->reset_code_expires_at && $this->reset_code_expires_at > now();
    }

    // After mag-reset ng password, i-clear na natin yung code para hindi na magamit ulit
    public function clearResetCode()
    {
        $this->reset_code = null;
        $this->reset_code_expires_at = null;
        $this->save();
    }

    // Scopes — para magamit sa queries like User::active()->get()
    // 'scopeActive' means puwede ko na i-call as 'active()'
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Dynamic scope with optional parameter — if may role na binigay, tsaka lang magfa-filter
    public function scopeByRole($query, $role)
    {
        if ($role) {
            return $query->where('role', $role);
        }
        return $query;
    }

    // Same pattern for status filtering
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Search scope — ginamit ko 'where(function($q) use ($search))' para i-group yung orWhere conditions
    // Kasi if walang group, mag-aapply si orWhere sa buong query — nakakaloka yun!
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
