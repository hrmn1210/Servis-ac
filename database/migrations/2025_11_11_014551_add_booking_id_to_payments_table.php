<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->after('service_request_id')->constrained('bookings')->onDelete('cascade');

            // Tambahkan index untuk performa
            $table->index('booking_id');
            $table->index('service_request_id');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn('booking_id');
        });
    }
};
