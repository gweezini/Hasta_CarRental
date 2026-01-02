<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Master Vouchers
        $v1 = \App\Models\Voucher::firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'name' => '10% Welcome Discount',
                'type' => 'percent',
                'value' => 10.00,
                'is_active' => true
            ]
        );

        $v2 = \App\Models\Voucher::firstOrCreate(
            ['code' => 'BDAY25'],
            [
                'name' => 'Birthday Special',
                'type' => 'percent',
                'value' => 25.00,
                'is_active' => true
            ]
        );

        $v3 = \App\Models\Voucher::firstOrCreate(
            ['code' => 'FREEHOUR'],
            [
                'name' => '1 Free Hour',
                'type' => 'fixed',
                'value' => 10.00, // Assuming 10 is the value of 1 hour, or just symbolic
                'is_active' => true
            ]
        );

        // 2. Assign to First User (for testing)
        $user = \App\Models\User::first();
        if ($user) {
            // Check if already assigned to avoid duplicates
            if (!$user->userVouchers()->where('voucher_id', $v1->id)->exists()) {
                \App\Models\UserVoucher::create([
                    'user_id' => $user->matric_staff_id,
                    'voucher_id' => $v1->id,
                ]);
            }

            if (!$user->userVouchers()->where('voucher_id', $v2->id)->exists()) {
                \App\Models\UserVoucher::create([
                    'user_id' => $user->matric_staff_id,
                    'voucher_id' => $v2->id,
                ]);
            }
        }
    }
}
