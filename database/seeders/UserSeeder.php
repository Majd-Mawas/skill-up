<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone_number' => '+963960965509',
            'password' => Hash::make('password'),
            'area_id' => 1,
            'email_verified_at' => now(),
            'phone_verified' => true,
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first());

        // Create trainer users
        for ($i = 1; $i <= 5; $i++) {
            $trainer = User::create([
                'name' => "Trainer {$i}",
                'email' => "trainer{$i}@example.com",
                'phone_number' => '+9639' . fake()->unique()->numberBetween(31000000, 99999999),
                'password' => Hash::make('password'),
                'area_id' => rand(1, 5),
                'email_verified_at' => now(),
                'phone_verified' => true,
            ]);
            $trainer->roles()->attach(Role::where('name', 'Trainer')->first());
        }

        // Create student users
        for ($i = 1; $i <= 20; $i++) {
            $student = User::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@example.com",
                'phone_number' => "+9639" . fake()->unique()->numberBetween(31000000, 99999999),
                'password' => Hash::make('password'),
                'area_id' => rand(1, 5),
                'email_verified_at' => now(),
                'phone_verified' => true,
            ]);
            $student->roles()->attach(Role::where('name', 'Student')->first());
        }

        // Create evaluator users
        for ($i = 1; $i <= 3; $i++) {
            $evaluator = User::create([
                'name' => "Evaluator {$i}",
                'email' => "evaluator{$i}@example.com",
                'phone_number' => "+9639" . fake()->unique()->numberBetween(31000000, 99999999),
                'password' => Hash::make('password'),
                'area_id' => rand(1, 5),
                'email_verified_at' => now(),
                'phone_verified' => true,
            ]);
            $evaluator->roles()->attach(Role::where('name', 'Evaluator')->first());
        }
    }
}
