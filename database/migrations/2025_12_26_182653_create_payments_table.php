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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        
        // This is the Foreign Key that connects to the Booking
        // In your ERD, a Payment "Belongs To" a Booking
        $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
        
        $table->decimal('amount', 8, 2); // Total amount (Rental + Deposit)
        $table->string('payment_method')->default('QR'); // Default as per your project
        
        // This stores the filename/path of the QR receipt image the student uploads
        $table->string('receipt_image')->nullable(); 
        
        $table->string('status')->default('Pending'); // Pending, Approved, or Rejected
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
