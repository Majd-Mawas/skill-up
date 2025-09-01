<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all training centers
        $trainingCenters = TrainingCenter::all();

        // Create realistic hall names in Arabic
        $hallNames = [
            'القاعة الأولى',
            'القاعة الثانية',
            'القاعة الثالثة',
            'القاعة الرابعة',
            'القاعة الخامسة',
            'قاعة 1',
            'قاعة 2',
            'قاعة 3',
            'قاعة التدريب الرئيسية',
            'قاعة المحاضرات الكبرى',
            'قاعة الندوات',
            'قاعة المؤتمرات',
            'قاعة التكنولوجيا',
            'قاعة الاجتماعات',
            'قاعة الحاسوب',
        ];

        // Hall descriptions
        $hallDescriptions = [
            'قاعة مجهزة بأحدث التقنيات التعليمية ومناسبة للدورات التدريبية',
            'قاعة واسعة ومريحة مناسبة للمحاضرات والندوات',
            'قاعة متعددة الاستخدامات مع إمكانية تعديل ترتيب المقاعد',
            'قاعة مخصصة للتدريب العملي مع أجهزة حاسوب حديثة',
            'قاعة فسيحة مناسبة للمجموعات الكبيرة والفعاليات',
            'قاعة مجهزة بأنظمة صوتية ومرئية متطورة',
            'قاعة هادئة مناسبة للاجتماعات والدورات الصغيرة',
            'قاعة مكيفة ومضاءة جيداً مع مقاعد مريحة',
            'قاعة مخصصة للتدريب التقني مع اتصال إنترنت عالي السرعة',
            'قاعة متميزة بتصميم عصري وإطلالة رائعة',
        ];

        // Create halls for each training center
        foreach ($trainingCenters as $index => $trainingCenter) {
            // Create 2-4 halls for each training center
            $numHalls = rand(2, 4);

            for ($i = 0; $i < $numHalls; $i++) {
                $nameIndex = ($index * 3 + $i) % count($hallNames);
                $descIndex = ($index * 2 + $i) % count($hallDescriptions);

                Hall::create([
                    'name' => $hallNames[$nameIndex],
                    'description' => $hallDescriptions[$descIndex],
                    'capacity' => fake()->numberBetween(15, 100),
                    'price_per_hour' => fake()->randomFloat(2, 100, 500),
                    'available' => true, // 80% chance of being available
                    'training_center_id' => $trainingCenter->id,
                ]);
            }
        }
    }
}
