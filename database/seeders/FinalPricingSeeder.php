<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingTier;
use App\Models\PricingRate;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class FinalPricingSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation/overwriting
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PricingRate::truncate();
        PricingTier::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tiers = [
            [
                'name' => 'Axia Old (V001-V003)',
                'description' => 'Pricing for older Axia models (2014-2016)',
                'vehicles' => ['V001', 'V002', 'V003'],
                'rates' => [
                    1 => 35,
                    3 => 55,
                    5 => 65,
                    7 => 70,
                    9 => 85,
                    12 => 95,
                    24 => 120
                ]
            ],
            [
                'name' => 'Axia New (V004-V005)',
                'description' => 'Pricing for newer Axia models (2024)',
                'vehicles' => ['V004', 'V005'],
                'rates' => [
                    1 => 40,
                    3 => 60,
                    5 => 70,
                    7 => 75,
                    9 => 90,
                    12 => 115,
                    24 => 140
                ]
            ],
            [
                'name' => 'Myvi (V006-V007)',
                'description' => 'Pricing for Myvi fleet',
                'vehicles' => ['V006', 'V007'],
                'rates' => [
                    1 => 40,
                    3 => 60,
                    5 => 70,
                    7 => 75,
                    9 => 90,
                    12 => 100,
                    24 => 130
                ]
            ],
            [
                'name' => 'Bezza (V008)',
                'description' => 'Pricing for Bezza fleet',
                'vehicles' => ['V008', 'V009'], // Including V009 as per previous context logic
                'rates' => [
                    1 => 50,
                    3 => 70,
                    5 => 80,
                    7 => 85,
                    9 => 95,
                    12 => 125,
                    24 => 150
                ]
            ]
        ];

        foreach ($tiers as $tierData) {
            $tier = PricingTier::create([
                'name' => $tierData['name'],
                'description' => $tierData['description']
            ]);

            foreach ($tierData['rates'] as $hour => $price) {
                PricingRate::create([
                    'pricing_tier_id' => $tier->id,
                    'hour_limit' => $hour,
                    'price' => $price
                ]);
            }

            foreach ($tierData['vehicles'] as $customId) {
                $vehicle = Vehicle::where('vehicle_id_custom', $customId)->first();
                if ($vehicle) {
                    $vehicle->update(['pricing_tier_id' => $tier->id]);
                    $this->command->info("Assigned $customId to tier: " . $tierData['name']);
                } else {
                    $this->command->warn("Vehicle $customId not found.");
                }
            }
        }
    }
}
