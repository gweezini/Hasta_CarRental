<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. 删除之前误创的 staff_id (如果你已经删了，这一行会自动跳过)
            if (Schema::hasColumn('users', 'staff_id')) {
                $table->dropColumn('staff_id');
            }

            // 2. 补上缺失的 account_holder 字段
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