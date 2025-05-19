<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'course_id' => \App\Models\Course::factory(),
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
