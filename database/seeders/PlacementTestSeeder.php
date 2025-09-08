<?php

namespace Database\Seeders;

use App\Models\PlacementTest;
use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class PlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all training centers
        $trainingCenters = TrainingCenter::all();
        
        // Define the four language tests
        $languageTests = [
            ['name' => 'English Placement Test'],
            ['name' => 'French Placement Test'],
            ['name' => 'Turkish Placement Test'],
            ['name' => 'Deutsch Placement Test'],
        ];
        
        // Create the four placement tests for each training center
        foreach ($trainingCenters as $trainingCenter) {
            foreach ($languageTests as $test) {
                PlacementTest::create([
                    'name' => $test['name'],
                    'training_center_id' => $trainingCenter->id,
                ]);
            }
            
            $this->command->info("Created placement tests for training center: {$trainingCenter->name}");
        }
        
        $this->command->info('Placement tests seeding completed!');
    }
}