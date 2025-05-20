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
            'certificate_number' => fake()->unique()->numerify('CERT-#####'),
            'issue_date' => fake()->date(),
            'expiry_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['active', 'expired', 'revoked']),
            'metadata' => ['test' => 'test'],
        ];
    }
}
