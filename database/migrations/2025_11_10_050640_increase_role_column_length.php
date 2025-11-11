<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Untuk MySQL
        DB::statement('ALTER TABLE users MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT "pengguna"');

        // Atau untuk database umum:
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('role', 20)->default('pengguna')->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY COLUMN role VARCHAR(10) NOT NULL DEFAULT "user"');

        // Atau untuk database umum:
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('role', 10)->default('user')->change();
        // });
    }
};
