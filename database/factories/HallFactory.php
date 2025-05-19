<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HallFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'capacity' => fake()->numberBetween(10, 100),
            'training_center_id' => \App\Models\TrainingCenter::factory(),
        ];
    }
}
