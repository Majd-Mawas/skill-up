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
                'name' => 'Information Technology',
                'description' => 'Courses related to programming, networking, and computer science',
            ],
            [
                'name' => 'Business Administration',
                'description' => 'Courses covering management, marketing, and business operations',
            ],
            [
                'name' => 'Language Learning',
                'description' => 'Courses for learning different languages and communication skills',
            ],
            [
                'name' => 'Professional Development',
                'description' => 'Courses focused on career growth and professional skills',
            ],
            [
                'name' => 'Technical Skills',
                'description' => 'Courses teaching practical technical skills and certifications',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
