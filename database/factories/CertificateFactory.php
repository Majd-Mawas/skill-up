<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
            'certificate_number' => fake()->unique()->numerify('CERT-#####'),
            'issue_date' => fake()->date(),
            'expiry_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement(['active', 'expired', 'revoked']),
            'metadata' => ['test' => 'test'],
        ];
    }
}
