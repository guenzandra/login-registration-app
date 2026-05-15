<!-- database/migrations/0001_01_01_000000_create_users_table.php -->
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('terms_accepted')->default(false);
            $table->timestamp('email_verified_at')->nullable();

            // Security fields
            $table->string('two_factor_secret')->nullable();     // For 2FA
            $table->string('two_factor_recovery_codes')->nullable(); // Backup codes
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->integer('login_attempts')->default(0);       // Track failed logins
            $table->timestamp('locked_until')->nullable();       // Account lockout
            $table->string('last_login_ip')->nullable();         // Track IP
            $table->timestamp('last_login_at')->nullable();      // Track last login
            $table->string('session_id')->nullable();            // Track active session

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Soft delete for account recovery

            // Indexes for performance
            $table->index('email');
            $table->index('locked_until');
            $table->index('session_id');
        });

        // Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Token expiration
        });

        // Sessions table for better session management
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Optional: Role-based access control
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, user, moderator
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Pivot table for user roles
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Activity logs for security auditing
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // login, logout, failed_login, password_reset, etc.
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};