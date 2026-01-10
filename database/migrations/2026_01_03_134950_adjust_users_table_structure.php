<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'staff_id')) {
                $table->dropColumn('staff_id');
            }

            if (!Schema::hasColumn('users', 'account_holder')) {
                $table->string('account_holder')->nullable()->after('account_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('staff_id')->nullable();
            $table->dropColumn('account_holder');
        });
    }
};