<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preference>
 */
class PreferenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'smoking' => $this->faker->boolean(),
            'pets' => $this->faker->boolean(),
            'travel' => $this->faker->boolean(),
            'nightlife' => $this->faker->boolean(),
        ];
    }
}
