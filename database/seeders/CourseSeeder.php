<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 courses
        $courses = Course::factory(20)->create();

        // Get all training centers
        $trainingCenters = TrainingCenter::all();

        // For each course, attach it to 2-4 random training centers
        foreach ($courses as $course) {
            $randomTrainingCenters = $trainingCenters->random(rand(2, 4));
            foreach ($randomTrainingCenters as $trainingCenter) {
                $course->trainingCenters()->attach($trainingCenter->id, [
                    'price' => fake()->numberBetween(100, 1000)
                ]);
            }
        }
    }
}
