<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 'up()' method — dito ilalagay yung changes na gusto mong gawin sa database
     * Like mag-add ng columns, mag-modify ng existing columns, mag-add ng indexes, etc.
     * 'void' means walang irereturn 'to, execute lang talaga siya
     */
    public function up(): void
    {
        // 'Schema::table('users')' — ginagamit 'to pag mag-i-edit ka ng existing table
        // Yung 'users' table yung a-affect-in nito
        // 'function (Blueprint $table)' — eto yung callback kung saan mo ide-define yung changes
        Schema::table('users', function (Blueprint $table) {
            // Empty for now — pero dito me maglalagay ng mga modifications, like:
            // $table->string('phone_number')->nullable()->after('email');
            // $table->dropColumn('old_column');
            // $table->renameColumn('old_name', 'new_name');
            // $table->index('new_column');
            //in the future.
        });
    }

    /**
     * Reverse the migrations.
     * 
     * 'down()' method — ito yung nagre-reverse ng ginawa mo sa up()
     * Pag nag-run ka ng 'php artisan migrate:rollback', eto yung nag-eexecute
     * Dapat dito mo inu-undo lahat ng ginawa mo sa up(), para consistent yung rollback
     * 'void' ulit — execute lang, no return value
     */
    public function down(): void
    {
        // 'Schema::table('users')' ulit — same table, pero ngayon i-u-undo yung changes
        Schema::table('users', function (Blueprint $table) {
            // Empty for now — pero dito mo ilalagay yung opposite ng ginawa mo sa up()
            // For example, kung nag-add ka ng column sa up(), dito mo siya ide-drop:
            // $table->dropColumn('phone_number');
        });
    }
};
