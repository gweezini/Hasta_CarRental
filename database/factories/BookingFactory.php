<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pickup = fake()->dateTimeBetween('-3 months', '+1 month');
        $return = fake()->dateTimeBetween($pickup, (clone $pickup)->modify('+'.fake()->numberBetween(1, 7).' days'));
        
        $statuses = ['Pending', 'Waiting for Verification', 'Approved', 'Rejected', 'Completed', 'Cancelled'];
        $status = fake()->randomElement($statuses);

        // Malaysian Faker
        $fakerMs = \Faker\Factory::create('ms_MY');

        // Fetch a random user completely
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'vehicle_id' => Vehicle::inRandomOrder()->value('id'),
            'pickup_date_time' => $pickup,
            'return_date_time' => $return,
            'pickup_location' => fake()->randomElement(['HQ', 'Airport', 'Train Station', 'Hotel', 'Custom Address']),
            'dropoff_location' => fake()->randomElement(['HQ', 'Airport', 'Train Station', 'Hotel', 'Custom Address']),
            'custom_pickup_address' => fake()->boolean(20) ? $fakerMs->address() : null,
            'custom_dropoff_address' => fake()->boolean(20) ? $fakerMs->address() : null,
            
            'customer_name' => $user->name,
            'customer_phone' => $user->phone_number ?? ('+601' . fake()->numberBetween(0, 9) . fake()->numberBetween(1000000, 9999999)),
            
            'emergency_contact_name' => $user->emergency_name ?? $fakerMs->name(),
            'emergency_contact_phone' => $user->emergency_contact ?? ('+601' . fake()->numberBetween(0, 9) . fake()->numberBetween(1000000, 9999999)),
            'emergency_relationship' => $user->emergency_relationship ?? fake()->randomElement(['Father', 'Mother', 'Sibling', 'Spouse', 'Friend']),
            
            'license_image' => 'licenses/sample_placeholder.jpg',
            'payment_receipt' => 'receipts/sample_placeholder.jpg',
            
            'total_rental_fee' => fake()->randomFloat(2, 50, 1000),
            'deposit_amount' => 50.00,
            'promo_code' => fake()->boolean(10) ? strtoupper(fake()->bothify('PROMO-####')) : null,
            
            'status' => $status,
            'deposit_status' => $status === 'Completed' ? 'Returned' : 'Pending',
            'deposit_receipt_path' => $status === 'Completed' ? 'receipts/deposit_sample.jpg' : null,
            'deposit_returned_at' => $status === 'Completed' ? $return->copy()->addHours(fake()->numberBetween(24, 48)) : null,
            
            'rejection_reason' => $status === 'Rejected' ? fake()->sentence() : null,
            'payment_verified' => in_array($status, ['Approved', 'Completed']),
            
            'refund_bank_name' => fake()->randomElement(['Maybayk', 'CIMB', 'Public Bank', 'RHB', 'Hong Leong', 'AmBank']),
            'refund_account_number' => fake()->bankAccountNumber(),
            'refund_recipient_name' => $user->name,

            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
