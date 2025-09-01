<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Interest;
use Illuminate\Database\Seeder;

class CategoryInterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map categories to related interests based on name similarity
        $categoryInterestMap = [
            'تكنولوجيا المعلومات' => ['التكنولوجيا'],
            'إدارة الأعمال' => ['الأعمال', 'المالية'],
            'تعلم اللغات' => ['اللغات'],
            'التطوير المهني' => ['الأعمال', 'التسويق'],
            'المهارات التقنية' => ['التكنولوجيا', 'التصميم'],
        ];

        foreach ($categoryInterestMap as $categoryName => $interestNames) {
            $category = Category::where('name', $categoryName)->first();
            
            if (!$category) {
                continue;
            }
            
            foreach ($interestNames as $interestName) {
                $interest = Interest::where('name', $interestName)->first();
                
                if (!$interest) {
                    continue;
                }
                
                // Attach the interest to the category
                $category->interests()->attach($interest->id);
            }
        }
    }
}