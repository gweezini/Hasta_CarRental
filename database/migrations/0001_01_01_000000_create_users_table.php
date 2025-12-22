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
        Schema::create('users', function (Blueprint $table) {
            // --- DEFAULT LARAVEL FIELDS ---
            $table->id();
            $table->string('name'); // Maps to 'FullName' in your Metadata
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // --- HASTA CUSTOM FIELDS (WHERE I MODIFIED) ---
            
            // 1. Identification [cite: 211, 245, 582]
            $table->string('matric_staff_id')->unique(); // For UTM Student Matric or Staff ID
            $table->string('nric_passport'); // NRIC/Passport Number [cite: 212, 248]
            $table->string('license_number'); // Driving License details [cite: 212, 248]

            // 2. Contact Information [cite: 605, 607]
            $table->string('phone_number')->nullable(); // CustomerContact
            $table->text('address')->nullable(); // CustomerAddress

            // 3. Emergency Contact [cite: 212, 246, 601, 603]
            $table->string('emergency_name')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_relation')->nullable();

            // 4. System & Logic [cite: 213, 216, 597, 608]
            $table->string('role')->default('student'); // student, staff, or manager
            $table->boolean('is_blacklisted')->default(false); // To block access 
            $table->unsignedBigInteger('college_id')->nullable(); // For College Summary Reports [cite: 231, 807]

            $table->rememberToken();
            $table->timestamps();
        });

        // These stay the same as your original file
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};