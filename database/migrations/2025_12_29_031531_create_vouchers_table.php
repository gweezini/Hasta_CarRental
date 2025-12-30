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
    // 1. The Master Voucher Table
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // e.g., "WELCOME10"
        $table->string('name'); // e.g., "Welcome Promo"
        $table->string('type'); // 'percent' or 'fixed'
        $table->decimal('value', 8, 2); // 10.00
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    // 2. The Wallet (Pivot) Table
    Schema::create('user_vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('user_id');
        $table->foreign('user_id')->references('matric_staff_id')->on('users')->onDelete('cascade');
        $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
        
        // This tells us if the user used it yet. NULL = Active. Date = Used.
        $table->timestamp('used_at')->nullable(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');
        Schema::dropIfExists('vouchers');
    }
};
