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
        // 1. Fix missing table issue
        if (!Schema::hasTable('inspections')) {
            Schema::create('inspections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
                $table->enum('type', ['pickup', 'return']);
                $table->integer('fuel_level'); 
                $table->integer('mileage');
                $table->text('remarks')->nullable();
                $table->json('photos')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
            });
        }

        // 2. Add processed_by column
        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'processed_by')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'processed_by')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['processed_by']);
                $table->dropColumn('processed_by');
            });
        }
        // We do not drop 'inspections' here because it might have been intended to be there from the previous migration
    }
};
