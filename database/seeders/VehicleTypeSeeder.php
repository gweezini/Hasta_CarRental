<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 1, 'name' => 'Compact (Axia)', 'base_price' => 8.00],
            ['code' => 2, 'name' => 'Sedan (Bezza)', 'base_price' => 10.00],
            ['code' => 3, 'name' => 'Hatchback (Myvi)', 'base_price' => 9.00],
            ['code' => 5, 'name' => 'Motorcycle', 'base_price' => 5.00],
        ];

        foreach ($types as $type) {
            VehicleType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}