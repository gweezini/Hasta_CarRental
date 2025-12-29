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
        Schema::table('bookings', function (Blueprint $table) {
            // Custom location addresses
            $table->text('custom_pickup_address')->nullable()->after('pickup_location');
            $table->text('custom_dropoff_address')->nullable()->after('dropoff_location');
            
            // Customer contact info
            $table->string('customer_name')->nullable()->after('dropoff_location');
            $table->string('customer_phone')->nullable()->after('customer_name');
            
            // Emergency contact info
            $table->string('emergency_contact_name')->nullable()->after('customer_phone');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            
            // File paths
            $table->string('license_image')->nullable()->after('emergency_contact_phone');
            $table->string('payment_receipt')->nullable()->after('license_image');
            
            // Voucher reference
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['voucher_id']);
            $table->dropColumn([
                'custom_pickup_address',
                'custom_dropoff_address',
                'customer_name',
                'customer_phone',
                'emergency_contact_name',
                'emergency_contact_phone',
                'license_image',
                'payment_receipt',
                'voucher_id',
            ]);
        });
    }
};
