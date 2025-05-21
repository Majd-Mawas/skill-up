<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Hall;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+2 months');

        return [
            'course_id' => Course::inRandomOrder()->first()->id,
            'hall_id' => Hall::inRandomOrder()->first()->id,
            'trainer_id' => User::inRandomOrder()->first()->id,
            'start_time' => $startDate,
            'end_time' => $endDate,
            'status' => fake()->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
