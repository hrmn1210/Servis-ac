<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('service_type');
            $table->text('description');
            $table->string('address');
            $table->string('phone_number');
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
