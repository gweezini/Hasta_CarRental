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
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Axia Grade A", "Myvi Grade B"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('pricing_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_tier_id')->constrained()->onDelete('cascade');
            $table->integer('hour_limit'); // 1, 3, 5, 7, 9, 12, 24
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('pricing_tier_id')->nullable()->constrained();
        });
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

        Schema::dropIfExists('pricing_rates');
        Schema::dropIfExists('pricing_tiers');
    }
};
