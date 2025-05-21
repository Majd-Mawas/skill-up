<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\TrainingCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseTrainingCenterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'training_center_id' => TrainingCenter::inRandomOrder()->first()->id,
            'price' => fake()->numberBetween(100, 1000),
        ];
    }
}
