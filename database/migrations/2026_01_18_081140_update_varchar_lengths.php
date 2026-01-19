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
        Schema::table('users', function (Blueprint $table) {
        $table->string('name', 100)->change();
        $table->string('email', 60)->change();
        $table->string('password', 255)->change();
        $table->string('role',15)->change();
        $table->string('matric_staff_id',15)->change();
        $table->string('phone_number',20)->change();
        $table->string('nationality',50)->change();
        $table->string('emergency_name',100)->change();
        $table->string('emergency_contact',20)->change();
        $table->string('emergency_relationship',20)->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
        $table->string('pickup_location', 50)->change();
        $table->string('dropoff_location', 50)->change();
        $table->string('customer_name', 50)->change();
        $table->string('customer_phone', 20)->change();
        $table->string('emergency_contact_name', 100)->change();
        $table->string('emergency_contact_phone', 20)->change();
        $table->string('emergency_relationship', 20)->change();
        $table->string('deposit_status', 20)->change();
        $table->string('refund_bank_name', 60)->change();
        $table->string('refund_account_number', 50)->change();
        $table->string('refund_recipient_name', 100)->change();
        });

        Schema::table('car_owners', function (Blueprint $table) {
        $table->string('name', 100)->change();
        $table->string('phone_number', 20)->change();
        });

        Schema::table('claims', function (Blueprint $table) {
        $table->string('matric_staff_id', 15)->change();
        $table->string('claim_type', 20)->change();
        $table->string('vehicle_plate', 10)->change();
        });

        Schema::table('colleges', function (Blueprint $table) {
        $table->string('name', 50)->change();//college name or "Outside Campus"
        });

        Schema::table('faculties', function (Blueprint $table) {
        $table->string('name', 100)->change();//Full name of faclulty such as Faculty of Built Environment and Surveying
        });

        Schema::table('feedback', function (Blueprint $table) {
        $table->string('category',50)->change();
        });

        Schema::table('fines', function (Blueprint $table) {
        $table->string('status',30)->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
        $table->string('email',60)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
        $table->string('payment_method',50)->change();
        $table->string('status',30)->change();
        });

        Schema::table('pricing_tiers', function (Blueprint $table) {
        $table->string('name',50)->change();
        });

        Schema::table('promotions', function (Blueprint $table) {
        $table->string('code',30)->change();
        $table->string('type',20)->change();
        });

        Schema::table('user_vouchers', function (Blueprint $table) {
        $table->string('user_id',15)->change();
        });

        Schema::table('vehicles', function (Blueprint $table) {
        $table->string('owner_name', 100)->change();
        $table->string('status', 30)->change();
        $table->string('plate_number', 10)->change();
        $table->string('vehicle_id_custom', 10)->change();
        });

        Schema::table('vehicle_type', function (Blueprint $table) {
        $table->string('name',30)->change();
        });

        Schema::table('vouchers', function (Blueprint $table) {
        $table->string('user_id',15)->change();
        $table->string('code',30)->change();
        $table->string('type',20)->change();
        $table->string('name',50)->change();
        });

        Schema::table('vehicles', function (Blueprint $table) {
        $table->dropColumn(['chassis_number', 'engine_number', 'owner_ic_number', 'insurance_policy_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
