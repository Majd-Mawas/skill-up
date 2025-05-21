<?php

namespace Database\Factories;

use App\Models\TrainingCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class HallFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'capacity' => fake()->numberBetween(10, 100),
            'price_per_hour' => fake()->randomFloat(2, 50, 500),
            'available' => fake()->boolean(),
            'training_center_id' => TrainingCenter::inRandomOrder()->first()->id,
        ];
    }
}
