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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            // Kolom ini menghubungkan ke tabel 'bookings'
            $table->foreignId('booking_id')
                ->constrained('bookings') // terhubung ke tabel 'bookings'
                ->onDelete('cascade');     // jika booking dihapus, rating ikut terhapus

            // Kolom ini menghubungkan ke tabel 'users' (siapa yang memberi rating)
            $table->foreignId('user_id')
                ->constrained('users') // terhubung ke tabel 'users'
                ->onDelete('cascade'); // jika user dihapus, rating ikut terhapus

            $table->tinyInteger('rating')->comment('Rating dari 1 sampai 5');
            $table->text('review')->nullable(); // Komentar/review (boleh kosong)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
