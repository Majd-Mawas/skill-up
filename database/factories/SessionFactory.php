<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+2 months');

        return [
            'course_id' => \App\Models\Course::factory(),
            'hall_id' => \App\Models\Hall::factory(),
            'trainer_id' => \App\Models\User::factory(),
            'start_time' => $startDate,
            'end_time' => $endDate,
            'status' => fake()->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
