<?php
// database/migrations/2026_05_15_122236_add_reset_code_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Anonymous class na naman 'to — para i-extend yung existing users table, hindi gumawa ng bago
return new class extends Migration
{
    // 'up()' — dito ako nag-a-add ng new columns sa existing table na 'users'
    // Hindi na 'Schema::create()' gamit ko kasi existing na yung table, 'Schema::table()' dapat
    public function up()
    {
        // 'Schema::table()' — nag-mo-modify ng existing table imbes na gumawa ng bago
        Schema::table('users', function (Blueprint $table) {

            // 'Schema::hasColumn()' — nagche-check muna ako kung existing na yung column bago i-add
            // Bakit? Kasi baka na-run na 'to before, or may ibang migration na gumawa na
            // Para iwas sa "column already exists" error — sobrang hassle maghanap ng fix pag nagka-error sa migration, ugh
            if (!Schema::hasColumn('users', 'reset_code')) {
                // 'string('reset_code', 6)' — VARCHAR column with maximum length of 6 characters
                // 6 digits lang kasi nga OTP-style yung reset code, hindi naman kailangan mahaba
                // 'nullable()' — pwedeng null kasi hindi naman laging may active reset request si user
                $table->string('reset_code', 6)->nullable();
            }

            // Same check for the expiration timestamp column
            if (!Schema::hasColumn('users', 'reset_code_expires_at')) {
                // 'timestamp()->nullable()' — stores datetime kung kelan mae-expire yung reset code
                // 'nullable()' — null meaning walang pending reset request
                // Usually i-set 'to sa now() + 15 minutes or 1 hour, depende sa security requirements
                $table->timestamp('reset_code_expires_at')->nullable();
            }
        });
    }

    // 'down()' — pag nag-rollback, ide-delete ko yung dalawang columns na 'to
    public function down()
    {
        // 'Schema::table()' ulit, pero ngayon magda-drop ng columns
        Schema::table('users', function (Blueprint $table) {
            // 'dropColumn()' — pwedeng mag-accept ng string or array of column names
            // Dine-delete ko pareho yung 'reset_code' at 'reset_code_expires_at' sabay-sabay
            // Hindi na need ng hasColumn check dito kasi kung wala naman yung columns, mag-eerror si dropColumn?
            // Actually oo, pero okay lang kasi rollback naman 'to — dapat talaga i-assume natin na existing sila
            $table->dropColumn(['reset_code', 'reset_code_expires_at']);
        });
    }
};
