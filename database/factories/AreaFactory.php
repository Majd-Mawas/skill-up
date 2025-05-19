<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'description' => fake()->paragraph(),
        ];
    }
}
