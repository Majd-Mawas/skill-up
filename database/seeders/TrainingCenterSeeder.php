<?php

namespace Database\Seeders;

use App\Enums\TrainingCenterStatus;
use App\Models\Area;
use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class TrainingCenterSeeder extends Seeder
{
    public function run(): void
    {
        // الحصول على جميع المناطق (أحياء حلب)
        $areas = Area::all();

        // قائمة بمراكز التدريب الواقعية في حلب
        $centers = [
            [
                'name' => 'معهد حلب للغات',
                'address' => 'العزيزية، الشارع الرئيسي',
                'phone_number' => '+963934567890',
                'email' => 'info@aleppoinstitute.com',
                'area_name' => 'العزيزية',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب لغات رائد يقدم دورات في اللغة الإنجليزية والفرنسية والألمانية والتركية بمستويات مختلفة ومعتمدة دولياً',
                'website' => 'https://aleppoinstitute.com',
            ],
            [
                'name' => 'أكاديمية حلب التقنية',
                'address' => 'حلب الجديدة، مجمع التكنولوجيا',
                'phone_number' => '+963945678901',
                'email' => 'contact@halabtech.edu',
                'area_name' => 'حلب الجديدة',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب تكنولوجي متقدم متخصص في البرمجة وتطوير الويب والتطبيقات وأمن المعلومات وإدارة الشبكات والذكاء الاصطناعي',
                'website' => 'https://halabtech.edu',
            ],
            [
                'name' => 'مركز الشهباء المهني',
                'address' => 'حي الشهباء، بناء 15',
                'phone_number' => '+963956789012',
                'email' => 'info@shahbapro.sy',
                'area_name' => 'الشهباء',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تطوير المهارات المهنية والأعمال يقدم دورات في المحاسبة والإدارة والتسويق وخدمة العملاء ومهارات البيع',
                'website' => 'https://shahbapro.sy',
            ],
            [
                'name' => 'معهد الحمدانية للمهارات',
                'address' => 'الحمدانية، طريق الجامعة',
                'phone_number' => '+963967890123',
                'email' => 'training@hamadaniahskills.com',
                'area_name' => 'الحمدانية',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب مهني يقدم دورات في المهارات العملية والحرفية مثل الكهرباء والسباكة والنجارة والحدادة والخياطة والتصميم الداخلي',
                'website' => 'https://hamadaniahskills.com',
            ],
            [
                'name' => 'أكاديمية صلاح الدين للفنون',
                'address' => 'صلاح الدين، شارع المركز الثقافي',
                'phone_number' => '+963978901234',
                'email' => 'arts@salaheddinacademy.sy',
                'area_name' => 'صلاح الدين',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب الفنون الجميلة والموسيقى والرسم والنحت والتصوير الفوتوغرافي والخط العربي والفنون التشكيلية',
                'website' => 'https://salaheddinacademy.sy',
            ],
            [
                'name' => 'مدرسة موغامبو للطهي',
                'address' => 'الشيخ مقصود، شارع الطعام 5',
                'phone_number' => '+963989012345',
                'email' => 'chef@mogamboculinary.com',
                'area_name' => 'الشيخ مقصود',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب محترف لفنون الطهي والحلويات والمخبوزات يقدم دورات في المطبخ العربي والعالمي على يد طهاة محترفين',
                'website' => 'https://mogamboculinary.com',
            ],
            [
                'name' => 'مركز الفرقان للتدريب الطبي',
                'address' => 'حي الفرقان، مبنى المركز الصحي',
                'phone_number' => '+963990123456',
                'email' => 'info@furqanmedical.edu',
                'area_name' => 'الفرقان',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب للمهنيين الطبيين والرعاية الصحية يقدم دورات في الإسعافات الأولية والتمريض ورعاية المسنين والصحة النفسية',
                'website' => 'https://furqanmedical.edu',
            ],
            [
                'name' => 'مركز الميدان للشباب',
                'address' => 'الميدان، ساحة الشباب',
                'phone_number' => '+963901234567',
                'email' => 'youth@midaancenter.org',
                'area_name' => 'الميدان',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تنمية الشباب والتدريب على المهارات الحياتية والقيادة وبناء الفريق وإدارة الوقت وحل المشكلات والتفكير الإبداعي',
                'website' => 'https://midaancenter.org',
            ],
            [
                'name' => 'كلية الخالدية للأعمال',
                'address' => 'الخالدية، شارع التجارة',
                'phone_number' => '+963912345678',
                'email' => 'admin@khalidiyahbusiness.com',
                'area_name' => 'الخالدية',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'تدريب في إدارة الأعمال وريادة الأعمال والتخطيط الاستراتيجي وإدارة الموارد البشرية والتمويل والاستثمار والتسويق الرقمي',
                'website' => 'https://khalidiyahbusiness.com',
            ],
            [
                'name' => 'معهد شارع النيل للتصميم',
                'address' => 'شارع النيل، مبنى المركز الإبداعي',
                'phone_number' => '+963923456789',
                'email' => 'design@nileinstitute.sy',
                'area_name' => 'شارع النيل',
                'status' => TrainingCenterStatus::ACTIVE->value,
                'description' => 'مركز تدريب التصميم الجرافيكي والفنون الرقمية وتصميم المواقع وتصميم الواجهات وتحرير الفيديو والرسوم المتحركة',
                'website' => 'https://nileinstitute.sy',
            ],
        ];

        // إنشاء كل مركز تدريب
        foreach ($centers as $centerData) {
            // البحث عن المنطقة بالاسم
            $area = $areas->where('name', $centerData['area_name'])->first();

            if ($area) {
                // إزالة area_name من البيانات وإضافة area_id
                unset($centerData['area_name']);
                $centerData['area_id'] = $area->id;

                // إنشاء مركز التدريب
                TrainingCenter::create($centerData);
            }
        }

        // إنشاء مراكز عشوائية إضافية للحصول على مزيد من التنوع
        TrainingCenter::factory()->count(10)->create();
    }
}
