<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Ubah tipe data preferred_time dari time menjadi varchar
            $table->string('preferred_time', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->time('preferred_time')->change();
        });
    }
};
