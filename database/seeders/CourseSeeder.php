<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use App\Models\TrainingCenter;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        // Get all training centers
        $trainingCenters = TrainingCenter::all();

        // IT courses
        $itCategory = $categories->where('name', 'تكنولوجيا المعلومات')->first();
        $itCourses = [
            [
                'name' => 'برمجة تطبيقات الويب',
                'description' => 'تعلم تطوير تطبيقات الويب باستخدام HTML, CSS, JavaScript وإطار العمل Laravel',
                'duration_hours' => 40,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['معرفة أساسية بالبرمجة', 'فهم أساسيات الإنترنت'],
                'learning_outcomes' => ['إنشاء مواقع ويب تفاعلية', 'استخدام إطار عمل Laravel', 'تطوير واجهات مستخدم جذابة']
            ],
            [
                'name' => 'أمن المعلومات والشبكات',
                'description' => 'دورة شاملة في أمن المعلومات وحماية الشبكات من الاختراقات',
                'duration_hours' => 35,
                'difficulty_level' => 'متقدم',
                'prerequisites' => ['معرفة بأساسيات الشبكات', 'خبرة في إدارة أنظمة التشغيل'],
                'learning_outcomes' => ['تحديد نقاط الضعف الأمنية', 'تنفيذ استراتيجيات الحماية', 'التعامل مع الهجمات الإلكترونية']
            ],
            [
                'name' => 'تطوير تطبيقات الهاتف المحمول',
                'description' => 'تعلم تطوير تطبيقات للأندرويد وiOS باستخدام React Native',
                'duration_hours' => 45,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['معرفة بلغة JavaScript', 'فهم أساسيات البرمجة'],
                'learning_outcomes' => ['إنشاء تطبيقات متعددة المنصات', 'التعامل مع واجهات المستخدم للجوال', 'نشر التطبيقات على المتاجر']
            ],
            [
                'name' => 'علم البيانات والذكاء الاصطناعي',
                'description' => 'مقدمة في علم البيانات وتطبيقات الذكاء الاصطناعي وتعلم الآلة',
                'duration_hours' => 50,
                'difficulty_level' => 'متقدم',
                'prerequisites' => ['معرفة بالإحصاء', 'خبرة في البرمجة بلغة Python'],
                'learning_outcomes' => ['تحليل مجموعات البيانات الكبيرة', 'بناء نماذج تعلم الآلة', 'تطبيق خوارزميات الذكاء الاصطناعي']
            ],
        ];

        // Business courses
        $businessCategory = $categories->where('name', 'إدارة الأعمال')->first();
        $businessCourses = [
            [
                'name' => 'إدارة المشاريع الاحترافية',
                'description' => 'تعلم أساسيات إدارة المشاريع والتحضير لشهادة PMP',
                'duration_hours' => 30,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['خبرة عملية في إدارة المشاريع', 'مهارات تنظيمية جيدة'],
                'learning_outcomes' => ['تخطيط وتنفيذ المشاريع بكفاءة', 'إدارة الموارد والميزانيات', 'تطبيق منهجيات إدارة المشاريع']
            ],
            [
                'name' => 'التسويق الرقمي',
                'description' => 'استراتيجيات التسويق عبر الإنترنت ووسائل التواصل الاجتماعي',
                'duration_hours' => 25,
                'difficulty_level' => 'مبتدئ',
                'prerequisites' => ['مهارات أساسية في استخدام الإنترنت', 'فهم أساسيات التسويق'],
                'learning_outcomes' => ['إنشاء حملات تسويقية رقمية', 'تحسين محركات البحث SEO', 'إدارة حسابات التواصل الاجتماعي']
            ],
            [
                'name' => 'ريادة الأعمال وإدارة الشركات الناشئة',
                'description' => 'كيفية بدء مشروعك الخاص وتطويره من الفكرة إلى التنفيذ',
                'duration_hours' => 35,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['شغف بريادة الأعمال', 'فهم أساسي للأعمال التجارية'],
                'learning_outcomes' => ['إعداد خطط الأعمال', 'جذب المستثمرين', 'إدارة النمو المستدام للشركات الناشئة']
            ],
        ];

        // Language courses
        $languageCategory = $categories->where('name', 'تعلم اللغات')->first();
        $languageCourses = [
            [
                'name' => 'اللغة الإنجليزية للأعمال',
                'description' => 'تطوير مهارات اللغة الإنجليزية في بيئة العمل والأعمال التجارية',
                'duration_hours' => 40,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['مستوى متوسط في اللغة الإنجليزية', 'الرغبة في تطوير المهارات اللغوية'],
                'learning_outcomes' => ['كتابة رسائل أعمال احترافية', 'إجراء مكالمات ومفاوضات بالإنجليزية', 'تقديم عروض تقديمية باللغة الإنجليزية']
            ],
            [
                'name' => 'اللغة العربية للناطقين بغيرها',
                'description' => 'تعليم اللغة العربية للأجانب بطريقة سهلة وفعالة',
                'duration_hours' => 45,
                'difficulty_level' => 'مبتدئ',
                'prerequisites' => ['لا توجد متطلبات مسبقة'],
                'learning_outcomes' => ['قراءة وكتابة النصوص العربية البسيطة', 'إجراء محادثات يومية باللغة العربية', 'فهم الثقافة العربية']
            ],
            [
                'name' => 'اللغة الفرنسية - المستوى المتقدم',
                'description' => 'تطوير مهارات اللغة الفرنسية للوصول إلى مستوى متقدم',
                'duration_hours' => 35,
                'difficulty_level' => 'متقدم',
                'prerequisites' => ['إتقان أساسيات اللغة الفرنسية', 'اجتياز المستوى المتوسط'],
                'learning_outcomes' => ['التحدث بطلاقة باللغة الفرنسية', 'فهم النصوص الأدبية والصحفية', 'كتابة مقالات متقدمة']
            ],
        ];

        // Professional development courses
        $professionalCategory = $categories->where('name', 'التطوير المهني')->first();
        $professionalCourses = [
            [
                'name' => 'مهارات التواصل الفعال',
                'description' => 'تطوير مهارات التواصل الشفهي والكتابي في بيئة العمل',
                'duration_hours' => 20,
                'difficulty_level' => 'مبتدئ',
                'prerequisites' => ['الرغبة في تحسين مهارات التواصل'],
                'learning_outcomes' => ['التحدث بثقة أمام الجمهور', 'كتابة رسائل مهنية فعالة', 'تحسين مهارات الاستماع النشط']
            ],
            [
                'name' => 'القيادة وإدارة الفرق',
                'description' => 'تطوير مهارات القيادة وإدارة فرق العمل بكفاءة',
                'duration_hours' => 30,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['خبرة عملية في بيئة العمل', 'مهارات تواصل جيدة'],
                'learning_outcomes' => ['قيادة الفرق بفعالية', 'حل النزاعات في مكان العمل', 'تحفيز الموظفين وتطوير أدائهم']
            ],
        ];

        // Technical skills courses
        $technicalCategory = $categories->where('name', 'المهارات التقنية')->first();
        $technicalCourses = [
            [
                'name' => 'التصميم الجرافيكي الاحترافي',
                'description' => 'تعلم أساسيات التصميم الجرافيكي باستخدام Adobe Photoshop و Illustrator',
                'duration_hours' => 40,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['اهتمام بالفنون البصرية', 'معرفة أساسية باستخدام الكمبيوتر'],
                'learning_outcomes' => ['إنشاء تصاميم احترافية', 'التعامل مع برامج Adobe', 'تطوير الهوية البصرية للعلامات التجارية']
            ],
            [
                'name' => 'إدارة قواعد البيانات',
                'description' => 'تعلم تصميم وإدارة قواعد البيانات باستخدام SQL و MySQL',
                'duration_hours' => 35,
                'difficulty_level' => 'متوسط',
                'prerequisites' => ['معرفة أساسية بمفاهيم البرمجة', 'فهم هيكلة البيانات'],
                'learning_outcomes' => ['تصميم قواعد بيانات فعالة', 'كتابة استعلامات SQL معقدة', 'تحسين أداء قواعد البيانات']
            ],
            [
                'name' => 'الذكاء الاصطناعي التطبيقي',
                'description' => 'تطبيقات عملية للذكاء الاصطناعي في مختلف المجالات',
                'duration_hours' => 45,
                'difficulty_level' => 'متقدم',
                'prerequisites' => ['معرفة بلغة Python', 'فهم أساسيات الإحصاء والرياضيات'],
                'learning_outcomes' => ['بناء نماذج الذكاء الاصطناعي', 'تطبيق تقنيات معالجة اللغة الطبيعية', 'تطوير حلول ذكية للمشكلات العملية']
            ],
        ];

        // Combine all courses
        $allCourses = [
            $itCategory->id => $itCourses,
            $businessCategory->id => $businessCourses,
            $languageCategory->id => $languageCourses,
            $professionalCategory->id => $professionalCourses,
            $technicalCategory->id => $technicalCourses,
        ];

        // Create courses for each category
        foreach ($allCourses as $categoryId => $courses) {
            foreach ($courses as $courseData) {
                $course = Course::create([
                    'name' => $courseData['name'],
                    'description' => $courseData['description'],
                    'category_id' => $categoryId,
                    'duration_hours' => $courseData['duration_hours'],
                    'difficulty_level' => $courseData['difficulty_level'],
                    'prerequisites' => $courseData['prerequisites'],
                    'learning_outcomes' => $courseData['learning_outcomes'],
                    'is_online' => fake()->boolean(),
                ]);

                // Attach to 2-4 random training centers
                $randomTrainingCenters = $trainingCenters->random(rand(2, 4));
                foreach ($randomTrainingCenters as $trainingCenter) {
                    // Generate random dates within next 6 months
                    $startDate = fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d');
                    $endDate = fake()->dateTimeBetween($startDate, '+2 months')->format('Y-m-d');

                    $course->trainingCenters()->attach($trainingCenter->id, [
                        'price' => fake()->numberBetween(500, 3000),
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'duration_hours' => fake()->numberBetween(20, 60)
                    ]);
                }
                
                // If course is online, assign 1-3 trainers to it
                if ($course->is_online) {
                    // Get trainers with Trainer role
                    $trainers = User::whereHas('roles', function($query) {
                        $query->where('name', Role::TRAINER->value);
                    })->inRandomOrder()->take(rand(1, 3))->get();
                    
                    foreach ($trainers as $trainer) {
                        // Generate random dates within next 6 months
                        $startDate = fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d');
                        $endDate = fake()->dateTimeBetween($startDate, '+4 months')->format('Y-m-d');
                        
                        $course->trainers()->attach($trainer->id, [
                            'price' => fake()->numberBetween(300, 2000),
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'notes' => fake()->optional(0.7)->sentence(),
                        ]);
                    }
                }
            }
        }
    }
}
