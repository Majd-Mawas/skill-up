<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'course_id' => \App\Models\Course::factory(),
            'certificate_number' => fake()->unique()->uuid(),
            'issue_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'expiry_date' => fake()->optional()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['active', 'expired', 'revoked']),
        ];
    }
}
