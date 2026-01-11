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
            $table->string('owner_name')->nullable()->after('is_hasta_owned');
            $table->string('owner_ic_path')->nullable()->after('owner_name');
            $table->string('owner_license_path')->nullable()->after('owner_ic_path');
            $table->string('geran_path')->nullable()->after('owner_license_path');
            $table->string('insurance_cover_path')->nullable()->after('geran_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'owner_name', 
                'owner_ic_path', 
                'owner_license_path', 
                'geran_path', 
                'insurance_cover_path'
            ]);
        });
    }
};
