<?php
// database/migrations/2026_05_15_075155_create_sessions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 'return new class extends Migration' — eto yung anonymous class syntax ng PHP
// Basically gumagawa ako ng instance ng Migration class on the fly, hindi ko na need pangalanan
// Ginagamit 'to ng Laravel para i-detect yung up() and down() methods automatically
return new class extends Migration
{
    // 'up()' method — dito ko nilalagay yung gagawin ng migration when I run 'php artisan migrate'
    // So like, sya yung magccreate ng table sa database, or mag-aadd ng columns, etc.
    public function up()
    {
        // 'Schema::hasTable()' — nagche-check muna ako kung exist na yung sessions table
        // Para iwas sa error, kasi kung meron na, wag na gumawa ulit diba? Very precautionary lang, ganern
        if (!Schema::hasTable('sessions')) {
            // 'Schema::create()' — ito yung actual na gumagawa ng table
            // 'Blueprint $table' — parang builder pattern 'to, para i-define yung structure ng columns
            Schema::create('sessions', function (Blueprint $table) {

                // 'string('id')->primary()' — eto yung session ID, string sya kasi hindi integer yung session IDs sa PHP
                // 'primary()' means sya yung unique identifier ng bawat row, so automatic may index na
                $table->string('id')->primary();

                // 'foreignId('user_id')->nullable()->index()' — eto yung reference sa users table
                // 'foreignId' is actually shorthand for unsignedBigInteger + foreign key constraint
                // 'nullable()' kasi puwedeng hindi logged in yung user (guest sessions)
                // 'index()' para mabilis yung paghahanap ng sessions ng specific user
                $table->foreignId('user_id')->nullable()->index();

                // 'ip_address' — naka 'string(45)' para magkasya both IPv4 (15 chars) and IPv6 (45 chars)
                // 'nullable()' kasi sometimes walang IP (like sa CLI or queues)
                $table->string('ip_address', 45)->nullable();

                // 'user_agent' — browser info to, 'text' kasi sobrang haba minsan ng user agent strings
                // 'nullable()' optional lang sya, no big deal if wala
                $table->text('user_agent')->nullable();

                // 'longText' — dito naka-store yung actual session data (serialized)
                // 'longText' ginamit ko instead of 'text' para walang limit — sessions can get big talaga
                $table->longText('payload');

                // 'integer('last_activity')' — timestamp kung kelan huling ginamit yung session
                // 'index()' — crucial 'to para sa session garbage collection (pagde-delete ng expired sessions)
                // Usually UNIX timestamp sya, kaya integer not datetime
                $table->integer('last_activity')->index();
            });
        }
    }

    // 'down()' method — ito yung nagre-reverse ng ginawa ng up() method
    // Kapag nag-run ka ng 'php artisan migrate:rollback', eto yung nag-eexecute
    public function down()
    {
        // 'Schema::dropIfExists()' — dine-delete yung sessions table if it exists
        // 'dropIfExists' gamit ko instead of 'drop' para iwas error kung wala naman yung table
        Schema::dropIfExists('sessions');
    }
};
