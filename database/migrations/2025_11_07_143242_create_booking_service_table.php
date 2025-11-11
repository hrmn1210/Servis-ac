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
        Schema::create('booking_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');

            $table->integer('quantity')->default(1); // Jumlah layanan, cth: 2 unit AC
            $table->decimal('price', 10, 2); // Harga layanan saat booking (untuk arsip)

            // $table->timestamps(); // Biasanya pivot table tidak perlu timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_service');
    }
};
