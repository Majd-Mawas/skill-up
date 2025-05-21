<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
            'amount' => fake()->numberBetween(100, 1000),
            'payment_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'cash']),
            'transaction_id' => fake()->uuid(),
            'status' => fake()->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'payment_details' => json_encode([
                'card_last4' => fake()->numberBetween(1000, 9999),
                'card_brand' => fake()->randomElement(['visa', 'mastercard', 'amex']),
            ]),
            'paid_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
