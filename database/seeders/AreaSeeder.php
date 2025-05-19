<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Riyadh', 'description' => 'The capital and largest city of Saudi Arabia'],
            ['name' => 'Jeddah', 'description' => 'A major port city on the Red Sea coast'],
            ['name' => 'Dammam', 'description' => 'The capital of the Eastern Province'],
            ['name' => 'Mecca', 'description' => 'The holiest city in Islam'],
            ['name' => 'Medina', 'description' => 'The second holiest city in Islam'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
