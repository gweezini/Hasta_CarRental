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
            $table->id(); //ok
            $table->string('name'); //ok
            $table->string('email')->unique(); //ok
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); //ok

            $table->string('role')->default('customer');
            $table->string('matric_staff_id')->unique(); //ok
            $table->string('nric_passport'); //ok
            $table->string('driving_license')->nullable(); //ok

            $table->string('phone_number')->nullable(); //ok
            $table->text('address')->nullable(); //ok

            $table->string('emergency_name')->nullable(); //ok
            $table->string('emergency_contact')->nullable(); //ok
            $table->string('emergency_relationship')->nullable(); //ok

          
            $table->string('role')->default('customer'); //ok
            $table->boolean('is_blacklisted')->default(false); //ok
            $table->foreignId('college_id')->nullable()->constrained(); //ok
            $table->foreignId('faculty_id')->nullable()->constrained();

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