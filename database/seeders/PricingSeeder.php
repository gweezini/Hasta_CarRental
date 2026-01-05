<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingTier;
use App\Models\Vehicle;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define Tiers and Rates
        $tiers = [
            'Axia V001-V003' => [
                'rates' => [
                    1 => 35, 3 => 55, 5 => 65, 7 => 70, 9 => 85, 12 => 95, 24 => 120
                ],
                'vehicles' => ['V001', 'V002', 'V003']
            ],
            'Axia V004-V005' => [
                'rates' => [
                    1 => 40, 3 => 60, 5 => 70, 7 => 75, 9 => 90, 12 => 115, 24 => 140
                ],
                'vehicles' => ['V004', 'V005']
            ],
            'Myvi V006-V007' => [
                'rates' => [
                    1 => 40, 3 => 60, 5 => 70, 7 => 75, 9 => 90, 12 => 100, 24 => 130
                ],
                'vehicles' => ['V006', 'V007']
            ],
            'Bezza V008' => [
                'rates' => [
                    1 => 50, 3 => 70, 5 => 80, 7 => 85, 9 => 95, 12 => 125, 24 => 150
                ],
                // V008 and V009 (assuming V009 is same as V008 or similar, prompt said Bezza V008 but V009 exists in seeder)
                'vehicles' => ['V008', 'V009'] 
            ],
            'Motor V010' => [
                'rates' => [
                    1 => 10, 3 => 18, 5 => 25, 7 => 31, 9 => 36, 12 => 40, 24 => 43
                ],
                'vehicles' => ['V010']
            ],
            'Motor V011' => [
                 'rates' => [
                    1 => 9, 3 => 17, 5 => 24, 7 => 30, 9 => 35, 12 => 39, 24 => 42
                ],
                'vehicles' => ['V011']
            ]
        ];

        foreach ($tiers as $name => $data) {
            $tier = PricingTier::create(['name' => $name]);

            foreach ($data['rates'] as $hours => $price) {
                $tier->rates()->create([
                    'hour_limit' => $hours,
                    'price' => $price
                ]);
            }

            foreach ($data['vehicles'] as $vCustomId) {
                Vehicle::where('vehicle_id_custom', $vCustomId)->update(['pricing_tier_id' => $tier->id]);
            }
        }
    }
}
