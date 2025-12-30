<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // VOUCHERS TABLE
        // 1) Add a temporary string column to hold matric/staff id
        if (!Schema::hasColumn('vouchers', 'user_id_str')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->string('user_id_str')->nullable()->after('id');
            });
        }

        // 2) If an old numeric user_id exists, copy over matric_staff_id values
        if (Schema::hasColumn('vouchers', 'user_id')) {
            DB::statement("UPDATE vouchers JOIN users ON users.id = vouchers.user_id SET vouchers.user_id_str = users.matric_staff_id WHERE vouchers.user_id IS NOT NULL");

            // drop foreign and old integer column if present
            Schema::table('vouchers', function (Blueprint $table) {
                try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
                try { $table->dropColumn('user_id'); } catch (\Exception $e) {}
            });
        }

        // 3) Create final string user_id column (if not exists) and copy from temp
        if (!Schema::hasColumn('vouchers', 'user_id')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->string('user_id')->nullable()->after('id');
            });

            DB::statement("UPDATE vouchers SET user_id = user_id_str WHERE user_id_str IS NOT NULL");

            // drop temp
            Schema::table('vouchers', function (Blueprint $table) {
                try { $table->dropColumn('user_id_str'); } catch (\Exception $e) {}
            });
        }

        // 4) Add foreign key to users.matric_staff_id
        Schema::table('vouchers', function (Blueprint $table) {
            try { $table->foreign('user_id')->references('matric_staff_id')->on('users')->onDelete('cascade'); } catch (\Exception $e) {}
        });

        // USER_VOUCHERS TABLE
        if (!Schema::hasColumn('user_vouchers', 'user_id_str')) {
            Schema::table('user_vouchers', function (Blueprint $table) {
                $table->string('user_id_str')->nullable()->after('id');
            });
        }

        if (Schema::hasColumn('user_vouchers', 'user_id')) {
            DB::statement("UPDATE user_vouchers JOIN users ON users.id = user_vouchers.user_id SET user_vouchers.user_id_str = users.matric_staff_id WHERE user_vouchers.user_id IS NOT NULL");

            Schema::table('user_vouchers', function (Blueprint $table) {
                try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
                try { $table->dropColumn('user_id'); } catch (\Exception $e) {}
            });
        }

        if (!Schema::hasColumn('user_vouchers', 'user_id')) {
            Schema::table('user_vouchers', function (Blueprint $table) {
                $table->string('user_id')->nullable()->after('id');
            });

            DB::statement("UPDATE user_vouchers SET user_id = user_id_str WHERE user_id_str IS NOT NULL");

            Schema::table('user_vouchers', function (Blueprint $table) {
                try { $table->dropColumn('user_id_str'); } catch (\Exception $e) {}
            });
        }

        Schema::table('user_vouchers', function (Blueprint $table) {
            try { $table->foreign('user_id')->references('matric_staff_id')->on('users')->onDelete('cascade'); } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the foreign keys and string columns we added.
        Schema::table('user_vouchers', function (Blueprint $table) {
            try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
            try { $table->dropColumn('user_id'); } catch (\Exception $e) {}
        });

        Schema::table('vouchers', function (Blueprint $table) {
            try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}
            try { $table->dropColumn('user_id'); } catch (\Exception $e) {}
        });
    }
};
