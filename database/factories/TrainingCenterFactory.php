<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingCenterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'website' => fake()->url(),
            'area_id' => \App\Models\Area::factory(),
            'category_id' => \App\Models\Category::factory(),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
