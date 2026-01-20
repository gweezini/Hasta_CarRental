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
        // 1. Drop foreign key and column from vehicles
        if (Schema::hasColumn('vehicles', 'car_owner_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                // We wrap this in a try-catch because depending on the DB state,
                // the foreign key might have a different auto-generated name 
                // or might not exist if previous migrations failed weirdly.
                // But normally 'vehicles_car_owner_id_foreign' is the standard Laravel name.
                try {
                    $table->dropForeign(['car_owner_id']);
                } catch (\Exception $e) {
                    // If FK doesn't exist, just continue
                }
                $table->dropColumn('car_owner_id');
            });
        }

        // 2. Drop the table
        Schema::dropIfExists('car_owners');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Recreate table
        if (!Schema::hasTable('car_owners')) {
            Schema::create('car_owners', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone_number');
                $table->timestamps();
            });
        }

        // 2. Re-add column and FK to vehicles
        if (Schema::hasTable('vehicles') && !Schema::hasColumn('vehicles', 'car_owner_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('car_owner_id')->nullable()->constrained('car_owners');
            });
        }
    }
};
