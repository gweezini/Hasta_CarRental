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
        if (!Schema::hasTable('pricing_tiers')) {
            Schema::create('pricing_tiers', function (Blueprint $table) {
                $table->id();
                $table->string('name'); 
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pricing_rules')) {
            Schema::create('pricing_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pricing_tier_id')->constrained('pricing_tiers')->cascadeOnDelete();
                $table->integer('hour_limit'); 
                $table->decimal('price', 8, 2);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('vehicles') && !Schema::hasColumn('vehicles', 'pricing_tier_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreignId('pricing_tier_id')->nullable()->constrained('pricing_tiers')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['pricing_tier_id']);
            $table->dropColumn('pricing_tier_id');
        });

        Schema::dropIfExists('pricing_rules');
        Schema::dropIfExists('pricing_tiers');
    }
};
