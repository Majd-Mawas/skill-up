<?php

namespace Database\Seeders;

use App\Models\ICDLTest;
use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class ICDLTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all training centers
        $trainingCenters = TrainingCenter::all();
        
        // Skip if no training centers
        if ($trainingCenters->isEmpty()) {
            return;
        }
        
        // Define ICDL test types
        $icdlTestTypes = [
            [
                'name' => 'ICDL Base Test',
                'description' => 'Basic computer skills test covering essential concepts of computing, file management, word processing, and spreadsheets.',
                'price' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Standard Test',
                'description' => 'Standard computer skills test covering all base modules plus presentations and databases.',
                'price' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Advanced Test',
                'description' => 'Advanced computer skills test covering advanced word processing, spreadsheets, databases, and presentations.',
                'price' => 250,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Expert Test',
                'description' => 'Expert level test for professional IT skills certification.',
                'price' => 300,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Digital Marketing Test',
                'description' => 'Specialized test for digital marketing concepts and skills.',
                'price' => 220,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL IT Security Test',
                'description' => 'Test focused on IT security concepts and best practices.',
                'price' => 180,
                'is_active' => true,
            ],
        ];
        
        // Create ICDL tests for each training center
        foreach ($trainingCenters as $trainingCenter) {
            // Select 2-4 random test types for each training center
            $selectedTestTypes = array_rand($icdlTestTypes, rand(2, min(4, count($icdlTestTypes))));
            
            // Ensure $selectedTestTypes is always an array
            if (!is_array($selectedTestTypes)) {
                $selectedTestTypes = [$selectedTestTypes];
            }
            
            // Create ICDL tests for the selected types
            foreach ($selectedTestTypes as $index) {
                $testType = $icdlTestTypes[$index];
                
                ICDLTest::create([
                    'training_center_id' => $trainingCenter->id,
                    'name' => $testType['name'],
                    'description' => $testType['description'],
                    'price' => $testType['price'],
                    'is_active' => $testType['is_active'],
                ]);
            }
        }
    }
}
