<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'course_id' => \App\Models\Course::factory(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
            'enrollment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'completion_date' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'grade' => fake()->optional()->numberBetween(0, 100),
            'feedback' => fake()->optional()->paragraph(),
        ];
    }
}
