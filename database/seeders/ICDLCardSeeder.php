<?php

namespace Database\Seeders;

use App\Models\ICDLCard;
use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class ICDLCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all training centers
        $trainingCenters = TrainingCenter::all();
        
        // ICDL card types with descriptions and prices
        $icdlCards = [
            [
                'name' => 'ICDL Basic Card',
                'description' => 'Basic ICDL certification covering computer essentials, online essentials, word processing, and spreadsheets.',
                'price' => 150.00,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Standard Card',
                'description' => 'Standard ICDL certification covering all basic modules plus presentations, databases, and IT security.',
                'price' => 250.00,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Advanced Card',
                'description' => 'Advanced ICDL certification covering advanced word processing, spreadsheets, databases, and presentations.',
                'price' => 350.00,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Professional Card',
                'description' => 'Professional ICDL certification covering project planning, web editing, image editing, and 2D CAD.',
                'price' => 450.00,
                'is_active' => true,
            ],
            [
                'name' => 'ICDL Workforce Card',
                'description' => 'Workforce ICDL certification covering digital marketing, IT security, and cloud computing essentials.',
                'price' => 300.00,
                'is_active' => true,
            ],
        ];
        
        // Create ICDL cards for each training center
        foreach ($trainingCenters as $trainingCenter) {
            // Each training center gets 2-3 random ICDL card types
            $randomCards = array_rand($icdlCards, rand(2, 3));
            
            foreach ($randomCards as $index) {
                $cardData = $icdlCards[$index];
                
                ICDLCard::create([
                    'name' => $cardData['name'],
                    'description' => $cardData['description'],
                    'price' => $cardData['price'],
                    'training_center_id' => $trainingCenter->id,
                    'is_active' => $cardData['is_active'],
                ]);
            }
        }
    }
}