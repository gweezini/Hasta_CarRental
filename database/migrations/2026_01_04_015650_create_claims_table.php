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
        Schema::create('claims', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->string('claim_type'); // fuel, car wash, etc.
            $blueprint->string('vehicle_plate')->nullable(); // Can be "Other"
            $blueprint->decimal('amount', 10, 2);
            $blueprint->dateTime('claim_date_time');
            $blueprint->text('description')->nullable();
            $blueprint->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $blueprint->text('action_reason')->nullable(); // Reason for approval/rejection
            $blueprint->foreignId('processed_by')->nullable()->constrained('users'); // Top Management ID
            $blueprint->timestamp('processed_at')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
