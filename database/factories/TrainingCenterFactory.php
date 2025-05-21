<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingCenterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone_number' => '+9639' . fake()->unique()->numberBetween(31000000, 99999999),
            'email' => fake()->companyEmail(),
            'area_id' => Area::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
