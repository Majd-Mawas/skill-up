<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingCenterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'area_id' => \App\Models\Area::factory(),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
