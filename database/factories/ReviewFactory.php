<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\TrainingCenter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $isCourseReview = fake()->boolean(70); // 70% chance of being a course review

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'course_id' => $isCourseReview ? Course::inRandomOrder()->first()->id : null,
            'training_center_id' => TrainingCenter::inRandomOrder()->first()->id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
        ];
    }

    /**
     * Create a review specifically for a course at a training center
     */
    public function courseReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'course_id' => Course::inRandomOrder()->first()->id,
            'training_center_id' => TrainingCenter::inRandomOrder()->first()->id,
        ]);
    }

    /**
     * Create a review specifically for a training center
     */
    public function trainingCenterReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'course_id' => null,
            'training_center_id' => TrainingCenter::inRandomOrder()->first()->id,
        ]);
    }
}
