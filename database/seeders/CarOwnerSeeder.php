<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarOwner;

class CarOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarOwner::create([
            'name' => 'Encik Rahim (External)',
            'phone_number' => '012-3456789',
        ]);

        CarOwner::create([
            'name' => 'Puan Siti (External)',
            'phone_number' => '019-8765432',
        ]);
    }
}
