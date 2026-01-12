<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => 'pickup', // Default
            'fuel_level' => fake()->numberBetween(1, 10),
            'mileage' => fake()->numberBetween(10000, 50000),
            'remarks' => fake()->sentence(),
            'photo_front' => 'inspections/sample_placeholder.jpg',
            'photo_back' => 'inspections/sample_placeholder.jpg',
            'photo_left' => 'inspections/sample_placeholder.jpg',
            'photo_right' => 'inspections/sample_placeholder.jpg',
            'photo_dashboard' => 'inspections/sample_placeholder.jpg',
            'photo_keys' => 'inspections/sample_placeholder.jpg',
            'created_by' => 1, // Admin default
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    public function returnInspection()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'return',
                'mileage' => $attributes['mileage'] + fake()->numberBetween(50, 500),
                'remarks' => fake()->randomElement(['No issues', 'Scratch on bumper', 'Clean return']),
            ];
        });
    }
}
