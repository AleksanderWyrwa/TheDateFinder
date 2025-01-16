<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeetingAvailability>
 */
class MeetingAvailabilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'type' => $this->faker->randomElement(['Dinner', 'Coffee', 'Walk', 'Movie']),
        ];
    }
}
