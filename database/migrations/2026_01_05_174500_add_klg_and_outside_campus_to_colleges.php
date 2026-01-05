<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\College;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $colleges = ['KLG', 'Outside Campus'];
        foreach ($colleges as $college) {
            College::firstOrCreate(['name' => $college]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        College::whereIn('name', ['KLG', 'Outside Campus'])->delete();
    }
};
