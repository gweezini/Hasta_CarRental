<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            ['code' => 'REWARD_3_STAMPS',  'val' => 10, 'type' => 'percentage', 'desc' => '10% Off (3 Stamps)'],
            ['code' => 'REWARD_6_STAMPS',  'val' => 15, 'type' => 'percentage', 'desc' => '15% Off (6 Stamps)'],
            ['code' => 'REWARD_9_STAMPS',  'val' => 20, 'type' => 'percentage', 'desc' => '20% Off (9 Stamps)'],
            ['code' => 'REWARD_12_STAMPS', 'val' => 25, 'type' => 'percentage', 'desc' => '25% Off (12 Stamps)'],
            ['code' => 'REWARD_15_STAMPS', 'val' => 12, 'type' => 'free_hours', 'desc' => '12 Hours Free (15 Stamps)'],
        ];

        foreach ($rewards as $r) {
            Promotion::updateOrCreate(
                ['code' => $r['code']],
                [
                    'discount_value' => $r['val'],
                    'type' => $r['type'],
                    'description' => $r['desc'], // I added this so your table isn't empty
                    'is_active' => true
                ]
            );
        }
    }
}
