<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_type', ['full', 'down_payment', 'cod'])->default('full')->after('status');
            $table->decimal('down_payment_amount', 10, 2)->nullable()->after('payment_type');
            $table->decimal('remaining_amount', 10, 2)->nullable()->after('down_payment_amount');
            $table->string('payment_proof')->nullable()->after('transaction_id');
            $table->text('admin_notes')->nullable()->after('payment_proof');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending')->after('admin_notes');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'down_payment_amount',
                'remaining_amount',
                'payment_proof',
                'admin_notes',
                'verification_status'
            ]);
        });
    }
};
