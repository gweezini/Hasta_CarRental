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

        // 2. Assign to the Logged-In User (Ali Student)
        // We try to find the specific user from the screenshot, or fall back to the first user.
        $user = \App\Models\User::where('email', 'student@utm.my')->first() ?? \App\Models\User::first();

        if ($user) {
            $this->command->info("Found User: " . $user->id . " | Matric: " . $user->matric_staff_id);

            try {
                // FLUSH existing vouchers
                $user->userVouchers()->delete();
                
                $vouchersToAssign = [$v1, $v2, $v3];

                foreach ($vouchersToAssign as $voucher) {
                    \App\Models\UserVoucher::create([
                        'user_id' => $user->matric_staff_id,
                        'voucher_id' => $voucher->id,
                    ]);
                    $this->command->info("Assigned voucher: " . $voucher->code);
                }
            } catch (\Exception $e) {
                $this->command->error("Error: " . $e->getMessage());
            }
        } else {
            $this->command->error("User student@utm.my not found.");
        }
    }
}
