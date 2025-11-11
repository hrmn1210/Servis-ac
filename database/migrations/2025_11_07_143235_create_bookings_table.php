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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Siapa yang memesan
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Siapa tukang servis yang ditugaskan (nullable karena awalnya kosong)
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');

            $table->text('address'); // Alamat lengkap servis (bisa beda dari alamat default user)
            $table->dateTime('booking_date'); // Jadwal servis yang diinginkan user
            $table->text('notes')->nullable(); // Catatan dari user (cth: "AC di kamar anak")

            // Status untuk tracking pesanan
            $table->enum('status', ['pending', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled'])
                ->default('pending');

            $table->decimal('total_price', 10, 2)->nullable(); // Total harga akhir

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
