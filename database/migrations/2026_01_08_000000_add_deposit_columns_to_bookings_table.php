<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('deposit_status')->default('Held')->after('status'); // Options: Held, Returned, Forfeited
            $table->string('deposit_receipt_path')->nullable()->after('deposit_status');
            $table->timestamp('deposit_returned_at')->nullable()->after('deposit_receipt_path');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['deposit_status', 'deposit_receipt_path', 'deposit_returned_at']);
        });
    }
};
