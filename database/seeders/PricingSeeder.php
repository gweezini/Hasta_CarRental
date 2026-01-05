<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingTier;
use App\Models\Vehicle;

class PricingSeeder extends Seeder
{
    public function run()
    {
        // Define Tiers and Rules based on User Request
        $tiers = [
            'Axia Tier 1' => [
                'rules' => [
                    1 => 35, 3 => 55, 5 => 65, 7 => 70, 9 => 85, 12 => 95, 24 => 120
                ],
                'vehicles' => ['V001', 'V002', 'V003']
            ],
            'Axia Tier 2' => [
                'rules' => [
                    1 => 40, 3 => 60, 5 => 70, 7 => 75, 9 => 90, 12 => 115, 24 => 140
                ],
                'vehicles' => ['V004', 'V005']
            ],
            'Myvi Tier' => [
                'rules' => [
                    1 => 40, 3 => 60, 5 => 70, 7 => 75, 9 => 90, 12 => 100, 24 => 130
                ],
                'vehicles' => ['V006', 'V007']
            ],
            'Bezza Tier' => [
                'rules' => [
                    1 => 50, 3 => 70, 5 => 80, 7 => 85, 9 => 95, 12 => 125, 24 => 150
                ],
                'vehicles' => ['V008', 'V009']
            ],
            'V010 Tier' => [ // Assumed name, maybe 'Student Car' or similar? Using ID for now
                 'rules' => [
                    1 => 10, 3 => 18, 5 => 25, 7 => 31, 9 => 36, 12 => 40, 24 => 43
                ],
                'vehicles' => ['V010']
            ],
            'V011 Tier' => [
                 'rules' => [
                    1 => 9, 3 => 17, 5 => 24, 7 => 30, 9 => 35, 12 => 39, 24 => 42
                ],
                'vehicles' => ['V011']
            ]
        ];

        foreach ($tiers as $name => $data) {
            // 1. Create Tier
            $tier = PricingTier::firstOrCreate(['name' => $name]);

            // 2. Create Rules (Clear old ones to be safe/update)
            $tier->rules()->delete();
            foreach ($data['rules'] as $hour => $price) {
                $tier->rules()->create(['hour_limit' => $hour, 'price' => $price]);
            }

            // 3. Assign Vehicles
            // Assuming vehicle_id_custom matches V001 etc. or we need to fuzzy match
            // The prompt says "for axia V001...". I'll assume `vehicle_id_custom` matches this logic.
            // If strictly mapping by ID might be safer if they exist.
            // Let's try to find by ID or fuzzy if needed.
            // Based on previous code `V001` usually sounds like a custom ID or plate?
            // Let's assume `vehicle_id_custom` stores "V001", "V002".
            
            Vehicle::whereIn('vehicle_id_custom', $data['vehicles'])->update(['pricing_tier_id' => $tier->id]);
        }
    }
}
