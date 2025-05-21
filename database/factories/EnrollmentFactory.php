<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
            'enrolled_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'completed_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'grade' => fake()->optional()->numberBetween(0, 100),
        ];
    }
}
