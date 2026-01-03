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
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('photos');
            $table->string('photo_front')->nullable()->after('remarks');
            $table->string('photo_back')->nullable()->after('photo_front');
            $table->string('photo_left')->nullable()->after('photo_back');
            $table->string('photo_right')->nullable()->after('photo_left');
            $table->string('photo_dashboard')->nullable()->after('photo_right');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['photo_front', 'photo_back', 'photo_left', 'photo_right', 'photo_dashboard']);
            $table->json('photos')->nullable()->after('remarks');
        });
    }
};
