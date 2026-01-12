<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory(50)->create()->each(function ($booking) {
            // Logic integrated from populate_relations.php
            if ($booking->status === 'Completed') {
                // Create PICKUP Inspection
                $pickup = \App\Models\Inspection::factory()->create([
                    'booking_id' => $booking->id,
                    'type' => 'pickup',
                    'created_at' => $booking->pickup_date_time,
                    'updated_at' => $booking->pickup_date_time,
                ]);
                
                // Create RETURN Inspection
                \App\Models\Inspection::factory()->returnInspection()->create([
                    'booking_id' => $booking->id,
                    'mileage' => $pickup->mileage + fake()->numberBetween(50, 500),
                    'created_at' => $booking->return_date_time,
                    'updated_at' => $booking->return_date_time,
                ]);
                
                // Create Feedback
                \App\Models\Feedback::factory()->create([
                    'booking_id' => $booking->id,
                    'created_at' => $booking->return_date_time->addHours(2),
                ]);
                
            } elseif ($booking->status === 'Approved') {
                // "Waiting Return" -> Has setup pickup inspection
                \App\Models\Inspection::factory()->create([
                    'booking_id' => $booking->id,
                    'type' => 'pickup',
                    'created_at' => $booking->pickup_date_time,
                    'updated_at' => $booking->pickup_date_time,
                ]);
            }
        });
    }
}
