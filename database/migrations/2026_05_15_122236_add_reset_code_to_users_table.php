<?php
// database/migrations/2026_05_15_122236_add_reset_code_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'reset_code')) {
                $table->string('reset_code', 6)->nullable();
            }
            if (!Schema::hasColumn('users', 'reset_code_expires_at')) {
                $table->timestamp('reset_code_expires_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reset_code', 'reset_code_expires_at']);
        });
    }
};