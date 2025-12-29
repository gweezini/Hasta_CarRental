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
        // 1. Create a Fixed Discount (RM 10 OFF)
        \App\Models\Voucher::create([
            'code' => 'WELCOME10',
            'name' => 'New User Promo',
            'type' => 'fixed',
            'value' => 10.00,
            'is_active' => true
        ]);

        // 2. Create a Percentage Discount (20% OFF)
        \App\Models\Voucher::create([
            'code' => 'RAYA2025',
            'name' => 'Raya Special',
            'type' => 'percent',
            'value' => 20.00,
            'is_active' => true
        ]);
    }
}
