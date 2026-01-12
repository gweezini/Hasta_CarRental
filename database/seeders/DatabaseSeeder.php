<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        CollegeSeeder::class,
        VehicleTypeSeeder::class,
        CarOwnerSeeder::class, 
        
        FacultySeeder::class,
        UserSeeder::class, 
        VehicleSeeder::class,
        VoucherSeeder::class,
        PricingSeeder::class,
          
        PromotionSeeder::class, 
    ]);
    
    // Generate 50 dummy users
    User::factory(50)->create();
    
    $this->call([
        BookingSeeder::class,
    ]);
    }
}
