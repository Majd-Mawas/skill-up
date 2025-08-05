<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'تكنولوجيا المعلومات',
                'description' => 'دورات متعلقة بالبرمجة والشبكات وعلوم الكمبيوتر',
            ],
            [
                'name' => 'إدارة الأعمال',
                'description' => 'دورات تغطي الإدارة والتسويق وعمليات الأعمال',
            ],
            [
                'name' => 'تعلم اللغات',
                'description' => 'دورات لتعلم اللغات المختلفة ومهارات التواصل',
            ],
            [
                'name' => 'التطوير المهني',
                'description' => 'دورات تركز على النمو الوظيفي والمهارات المهنية',
            ],
            [
                'name' => 'المهارات التقنية',
                'description' => 'دورات لتعليم المهارات التقنية العملية والشهادات',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
