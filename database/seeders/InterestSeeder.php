<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            [
                'name' => 'التكنولوجيا',
                'description' => 'البرمجة وتطوير البرمجيات واتجاهات التكنولوجيا',
                'is_active' => true,
            ],
            [
                'name' => 'الأعمال',
                'description' => 'ريادة الأعمال والإدارة واستراتيجيات الأعمال',
                'is_active' => true,
            ],
            [
                'name' => 'التصميم',
                'description' => 'التصميم الجرافيكي وواجهة المستخدم/تجربة المستخدم والفنون الإبداعية',
                'is_active' => true,
            ],
            [
                'name' => 'التسويق',
                'description' => 'التسويق الرقمي ووسائل التواصل الاجتماعي والإعلان',
                'is_active' => true,
            ],
            [
                'name' => 'اللغات',
                'description' => 'تعلم لغات جديدة ومهارات التواصل',
                'is_active' => true,
            ],
            [
                'name' => 'الصحة واللياقة البدنية',
                'description' => 'العافية الجسدية والتغذية ونمط الحياة الصحي',
                'is_active' => true,
            ],
            [
                'name' => 'المالية',
                'description' => 'المالية الشخصية والاستثمار والتخطيط المالي',
                'is_active' => true,
            ],
            [
                'name' => 'الفنون والحرف اليدوية',
                'description' => 'الهوايات الإبداعية والحرف اليدوية والتعبير الفني',
                'is_active' => true,
            ],
            [
                'name' => 'العلوم',
                'description' => 'البحث العلمي والاكتشافات والابتكارات',
                'is_active' => true,
            ],
            [
                'name' => 'السفر',
                'description' => 'استكشاف أماكن جديدة والثقافات والتجارب',
                'is_active' => true,
            ],
        ];

        foreach ($interests as $interest) {
            Interest::create($interest);
        }
    }
}
