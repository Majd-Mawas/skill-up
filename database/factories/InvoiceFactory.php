<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\TrainingCenter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
            'training_center_id' => TrainingCenter::inRandomOrder()->first()->id,
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
