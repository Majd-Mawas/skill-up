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
            'training_center_id' => \App\Models\TrainingCenter::factory(),
            'payment_id' => null,
            'invoice_number' => fake()->unique()->numerify('INV-#####'),
            'subtotal' => $subtotal = fake()->numberBetween(100, 1000),
            'tax' => $tax = $subtotal * 0.15,
            'total' => $subtotal + $tax,
            'status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
            'due_date' => fake()->date(),
            'paid_date' => fake()->optional()->date(),
        ];
    }
}
