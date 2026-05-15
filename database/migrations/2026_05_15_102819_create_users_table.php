<?php
// database/migrations/2025_01_15_000000_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Another anonymous class — para i-auto-detect ni Laravel 'to without needing a class name
return new class extends Migration
{
    // 'up()' — when we migrate forward, ito yung nagccreate ng users table
    public function up()
    {
        // 'Schema::create()' — creates a new table named 'users'
        // 'Blueprint $table' — parang blueprint ng bahay, dito ko idesign yung columns
        Schema::create('users', function (Blueprint $table) {

            // '$table->id()' — shortcut for auto-incrementing primary key (BIGINT unsigned)
            // Instead of writing $table->bigIncrements('id') — same lang, pero mas short and sweet
            $table->id();

            // 'string()' — creates VARCHAR column
            // First and last name, walang nilagay na length so default is 255 chars
            // More than enough naman for names, unless superhaba ng pangalan mo bhie
            $table->string('first_name');
            $table->string('last_name');

            // 'email' column na may 'unique()' — ibig sabihin hindi pwedeng magkaroon ng duplicate emails
            // Kasi each user dapat unique email nila for login, diba? Common sense talaga
            $table->string('email')->unique();

            // 'password' — dito nakastore yung hashed password (bcrypt/argon2)
            // Never store plain text passwords, very dangerous yun! So wag please lang
            $table->string('password');

            // 'enum()' — restricts the values to specific choices lang
            // Parang dropdown sa database level — 'admin', 'mod', or 'user' lang talaga
            // 'default('user')' — so pag walang sinet, automatically 'user' siya
            $table->enum('role', ['admin', 'mod', 'user'])->default('user');

            // Another enum for account status — 'active', 'inactive', or 'banned'
            // Default is 'active' para pag nag-register, pwede agad magamit
            // 'banned' is for users na nag-violate ng rules, so lockdown sila yarn
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');

            // 'timestamp()->nullable()' — pwedeng null kasi hindi naman agad verified si user
            // Pag nag-verify na sila ng email, saka ko lang i-seset 'to to current timestamp
            $table->timestamp('email_verified_at')->nullable();

            // 'rememberToken()' — shortcut for 'remember_token' column (VARCHAR 100, nullable)
            // Ito yung ginagamit ng Laravel for "Remember Me" functionality
            // Stores token para ma-automatic login si user kahit closed na yung browser
            $table->rememberToken();

            // 'timestamps()' — creates 'created_at' and 'updated_at' columns (both timestamps)
            // Auto-managed ni Laravel 'to — pag nag-create ka ng record, may created_at
            // Pag nag-update, auto-update din si updated_at. Sobrang convenient, promise!
            $table->timestamps();

            // 'index()' — adds a regular index (not unique) para mapabilis ang queries
            // Composite index 'to sa role and status — useful for filtering users by role+status
            // Example: WHERE role = 'admin' AND status = 'active' — mabilis 'to because of this index
            $table->index(['role', 'status']);

            // Another index specifically for 'email' column
            // Kahit may unique constraint na siya, may index pa rin (unique automatically creates index)
            // Pero I'm being explicit lang para malinaw sa ibang devs na importante 'to for lookups
            $table->index('email');
        });
    }

    // 'down()' — rollback method, dine-delete yung users table if mag migrate:rollback
    public function down()
    {
        // 'dropIfExists()' — safe way to drop, hindi mag-eerror kahit wala na yung table
        Schema::dropIfExists('users');
    }
};
