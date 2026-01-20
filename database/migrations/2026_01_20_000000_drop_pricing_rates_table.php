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
        Schema::dropIfExists('pricing_rates');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('pricing_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_tier_id')->constrained()->onDelete('cascade');
            $table->integer('hour_limit');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }
};
