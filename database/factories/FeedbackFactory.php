<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ratings' => [
                'issue_interior' => fake()->boolean(10),
                'issue_smell' => fake()->boolean(10),
                'issue_mechanical' => fake()->boolean(5),
                'issue_ac' => fake()->boolean(5),
                'issue_exterior' => fake()->boolean(10),
                'issue_safety' => fake()->boolean(2),
                'issue_safety' => fake()->boolean(2),
            ],
            'category' => fake()->randomElement(['Cleanliness', 'Maintenance', 'Service', 'Other']),
            'description' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
