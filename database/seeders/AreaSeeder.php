<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areasData = [
            ['name' => 'العزيزية'],
            ['name' => 'الجميلية'],
            ['name' => 'سيف الدولة'],
            ['name' => 'صلاح الدين'],
            ['name' => 'بستان القصر'],
            ['name' => 'الأنصاري'],
            ['name' => 'المشهد'],
            ['name' => 'السكري'],
            ['name' => 'الشعار'],
            ['name' => 'الصاخور'],
            ['name' => 'الهلك'],
            ['name' => 'الشيخ مقصود'],
            ['name' => 'الراموسة'],
            ['name' => 'الزبدية'],
            ['name' => 'حلب الجديدة'],
            ['name' => 'الحمدانية'],
            ['name' => 'الأعظمية'],
            ['name' => 'الفرقان'],
            ['name' => 'السريان القديمة'],
            ['name' => 'السريان الجديدة'],
            ['name' => 'الميدان'],
            ['name' => 'الخالدية'],
            ['name' => 'شارع النيل'],
            ['name' => 'الشهباء'],
        ];



        $areas = collect();
        foreach ($areasData as $data) {
            $areas[$data['name']] = Area::create([
                'name' => $data['name'],
                'description' => null,
                'centroid' => null,
            ]);
        }
        $neighborPairs = [
            ['العزيزية', 'الجميلية'],
            ['الجميلية', 'الفرقان'],
            ['الفرقان', 'السريان الجديدة'],
            ['السريان الجديدة', 'السريان القديمة'],
            ['السريان القديمة', 'الميدان'],
            ['الأعظمية', 'حلب الجديدة'],
            ['حلب الجديدة', 'الحمدانية'],
            ['الحمدانية', 'الراموسة'],
            ['الراموسة', 'الأنصاري'],
            ['الأنصاري', 'المشهد'],
            ['المشهد', 'السكري'],
            ['السكري', 'الزبدية'],
            ['الزبدية', 'صلاح الدين'],
            ['صلاح الدين', 'سيف الدولة'],
            ['سيف الدولة', 'بستان القصر'],
            ['بستان القصر', 'الشعار'],
            ['الشعار', 'الصاخور'],
            ['الصاخور', 'الهلك'],
            ['الهلك', 'الشيخ مقصود'],
            ['الخالدية', 'شارع النيل'],
            ['الخالدية', 'الشهباء'],
            ['شارع النيل', 'الشهباء'],
        ];


        foreach ($neighborPairs as [$a, $b]) {
            $areaA = $areas[$a];
            $areaB = $areas[$b];

            DB::table('area_neighbors')->insert([
                'area_id' => min($areaA->id, $areaB->id),
                'neighbor_id' => max($areaA->id, $areaB->id),
            ]);
        }
    }
}
