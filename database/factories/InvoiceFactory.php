<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'course_id' => \App\Models\Course::factory(),
            'amount' => fake()->numberBetween(100, 1000),
            'status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'paid_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
