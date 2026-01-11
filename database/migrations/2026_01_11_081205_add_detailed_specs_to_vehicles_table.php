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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('chassis_number')->nullable()->after('plate_number');
            $table->string('engine_number')->nullable()->after('chassis_number');
            $table->string('owner_ic_number')->nullable()->after('owner_name');
            $table->string('insurance_policy_number')->nullable()->after('insurance_cover_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'chassis_number',
                'engine_number',
                'owner_ic_number',
                'insurance_policy_number'
            ]);
        });
    }
};
