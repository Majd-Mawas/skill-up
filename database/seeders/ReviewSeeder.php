<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\TrainingCenter;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $courses = Course::all();
        $trainingCenters = TrainingCenter::all();

        // Create reviews for courses at specific training centers
        foreach ($courses as $course) {
            $courseTrainingCenters = $course->trainingCenters;

            foreach ($courseTrainingCenters as $trainingCenter) {
                // Create 3-7 reviews for each course at each training center
                $reviewCount = rand(3, 7);

                for ($i = 0; $i < $reviewCount; $i++) {
                    Review::factory()->create([
                        'user_id' => $users->random()->id,
                        'course_id' => $course->id,
                        'training_center_id' => $trainingCenter->id,
                        'rating' => rand(1, 5),
                    ]);
                }
            }
        }

        // Create general reviews for training centers
        foreach ($trainingCenters as $trainingCenter) {
            // Create 5-10 general reviews for each training center
            $reviewCount = rand(5, 10);

            for ($i = 0; $i < $reviewCount; $i++) {
                Review::factory()->create([
                    'user_id' => $users->random()->id,
                    'course_id' => null,
                    'training_center_id' => $trainingCenter->id,
                    'rating' => rand(1, 5),
                ]);
            }
        }
    }
}
