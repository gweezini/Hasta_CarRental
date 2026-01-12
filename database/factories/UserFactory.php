<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Weighted role randomization: 90% Customer, 5% Admin, 5% Top Mgmt
        $role = fake()->randomElement(array_merge(
            array_fill(0, 18, 'customer'), 
            ['admin', 'topmanagement']
        ));

        $isCustomer = $role === 'customer';
        $isStaff = in_array($role, ['admin', 'topmanagement']);

        // Use Malaysian Faker for local names/addresses
        $fakerMs = \Faker\Factory::create('ms_MY');

        return [
            // --- Identity ---
            'name' => $fakerMs->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('12345'), // Default password
            'remember_token' => Str::random(10),
            
            'matric_staff_id' => $isCustomer 
                ? 'A2' . fake()->numberBetween(1, 5) . 'EC' . fake()->numberBetween(1000, 9999) 
                : 'S' . fake()->unique()->numberBetween(000, 999),
            
            'nric_passport' => fake()->numerify('############'),
            'phone_number' => '+601' . fake()->numberBetween(0, 9) . fake()->numberBetween(1000000, 9999999),

            // --- Role & Status ---
            'role' => $role,
            'is_blacklisted' => fake()->boolean(5), // 5% chance
            'blacklist_reason' => null,

            // --- Demographics & Student Info ---
            'nationality' => 'Malaysian',
            'address' => $fakerMs->address() . ', ' . $fakerMs->city() . ', ' . $fakerMs->state(), 
            'expired_date' => $isCustomer ? fake()->dateTimeBetween('now', '+3 years')->format('Y-m-d') : null,
            'college_id' => $isCustomer ? fake()->numberBetween(1, 12) : null,
            'faculty_id' => $isCustomer ? fake()->numberBetween(1, 13) : null,

            // --- Emergency Contact ---
            'emergency_name' => $fakerMs->name(),
            'emergency_contact' => '+601' . fake()->numberBetween(0, 9) . fake()->numberBetween(1000000, 9999999),
            'emergency_relationship' => fake()->randomElement(['Father', 'Mother', 'Sibling', 'Guardian']),

            // --- Financial (Staff Only) ---
            'bank_name' => $isStaff ? fake()->randomElement(['Maybank', 'CIMB', 'Public Bank', 'RHB', 'Hong Leong Bank', 'UOB']) : null,
            'account_number' => $isStaff ? fake()->bankAccountNumber() : null,
            'account_holder' => $isStaff ? fake()->name() : null,
            'salary' => $isStaff ? fake()->randomFloat(2, 1800, 8000) : 0.00,

            // --- Files (Placeholders) ---
            'matric_card_path' => 'matric_cards/sample_placeholder.jpg',
            'driving_license_path' => 'licenses/sample_placeholder.jpg',
            'nric_passport_path' => 'nric/sample_placeholder.jpg',
            
            // --- Timestamps ---
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
