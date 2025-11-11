<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable(); // Untuk login admin/user dgn username
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // Nullable untuk user yang login via Google
            $table->string('google_id')->nullable()->unique(); // Menyimpan ID dari Google

            // Role: admin, user (pelanggan), technician (tukang servis)
            $table->enum('role', ['admin', 'user', 'technician'])->default('user');

            $table->string('phone_number')->nullable();
            $table->text('address')->nullable(); // Alamat default user

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
