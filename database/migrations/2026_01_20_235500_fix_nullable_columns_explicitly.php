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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('owner_name', 100)->nullable()->change();
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('user_id', 15)->nullable()->change();
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_name', 150)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 20)->nullable()->change();
            $table->string('nationality', 100)->nullable()->change();
            $table->string('emergency_name', 100)->nullable()->change();
            $table->string('emergency_contact', 20)->nullable()->change();
            $table->string('emergency_relationship', 20)->nullable()->change();
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
