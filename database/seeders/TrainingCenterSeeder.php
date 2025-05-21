<?php

namespace Database\Seeders;

use App\Models\TrainingCenter;
use Illuminate\Database\Seeder;

class TrainingCenterSeeder extends Seeder
{
    public function run(): void
    {
        TrainingCenter::factory()->count(20)->create();
        // $centers = [
        //     [
        //         'name' => 'Tech Academy',
        //         'address' => 'King Fahd Road, Riyadh',
        //         'phone_number' => '0112345678',
        //         'email' => 'info@techacademy.com',
        //         'area_id' => 1,
        //         'status' => 'active',
        //     ],
        //     [
        //         'name' => 'Business Skills Institute',
        //         'address' => 'Prince Mohammed Street, Jeddah',
        //         'phone_number' => '0123456789',
        //         'email' => 'contact@bsi.com',
        //         'area_id' => 2,
        //         'status' => 'active',
        //     ],
        //     [
        //         'name' => 'Language Center',
        //         'address' => 'King Abdullah Road, Dammam',
        //         'phone_number' => '0134567890',
        //         'email' => 'info@languagecenter.com',
        //         'area_id' => 3,
        //         'status' => 'active',
        //     ],
        // ];

        // foreach ($centers as $center) {
        //     TrainingCenter::create($center);
        // }
    }
}
