<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->decimal('salary', 10, 2)->default(0); 
            $table->string('staff_id')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'salary', 'staff_id']);
        });
    }
};