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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('vehicle_id_custom')->unique();
        $table->string('plate_number')->unique();
        $table->string('brand');
        $table->string('model');
        $table->integer('year');
        $table->integer('capacity')->default(5);
        $table->decimal('price_per_hour', 8, 2)->default(10.00);
        
        // --- OWNERSHIP LOGIC ---
        $table->boolean('is_hasta_owned')->default(true); // The Switch
        
        // NEW LINE: Links to the 'car_owners' table
        // We make it 'nullable' because Hasta cars don't have an external owner
        $table->foreignId('car_owner_id')->nullable()->constrained('car_owners');
        
        $table->integer('current_fuel_bars');
        $table->string('status')->default('Available');
        
        // Note: You need to make sure 'vehicle_types' table exists before this line runs!
        // If this gives an error, we might need to change 'integer' to 'foreignId' later
        // But for now, since you use raw IDs (1,2,3), integer is fine.
        $table->integer('type_id'); 
        
        $table->date('road_tax_expiry')->nullable();
        $table->date('insurance_expiry')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
