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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id(); // Primary Key: BookingID [cite: 531]

        // --- RELATIONSHIPS (The lines in your ERD) ---
        // Links to the 'users' table (Matric/Staff ID) [cite: 531, 546]
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // Links to the 'vehicles' table (Plate Number) [cite: 531, 547]
        $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');

        // --- BOOKING DETAILS ---
        $table->dateTime('pickup_date_time'); // [cite: 219, 631]
        $table->dateTime('return_date_time'); // [cite: 219, 644]
        $table->string('pickup_location')->default('Student Mall'); // [cite: 258, 844]
        $table->string('dropoff_location')->default('Student Mall'); // [cite: 258, 844]
        
        // --- FINANCIALS ---
        $table->decimal('total_rental_fee', 8, 2); // [cite: 531, 651]
        $table->decimal('deposit_amount', 8, 2); // [cite: 266, 851]
        $table->string('promo_code')->nullable(); // [cite: 531, 545]
        
        // --- STATUS & TRACKING ---
        // Status can be: Pending, Confirmed, Cancelled, Returning, Completed [cite: 344, 410]
        $table->string('status')->default('Pending'); 
        $table->boolean('payment_verified')->default(false); // For staff manual verification 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
