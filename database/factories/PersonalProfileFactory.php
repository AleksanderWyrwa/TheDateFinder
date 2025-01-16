<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalProfile>
 */
class PersonalProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'birth_date' => $this->faker->date('Y-m-d', '-18 years'),
            'description' => $this->faker->paragraph(),
            'height' => $this->faker->numberBetween(150, 200),
            'weight' => $this->faker->numberBetween(50, 100),
        ];
    }
}
