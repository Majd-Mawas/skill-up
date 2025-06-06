<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(1)->create([
        //     'name' => 'Konrix',
        //     'email' => 'konrix@coderthemes.com',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);

        $this->call([
            AreaSeeder::class,
            AreaNeighborSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TrainingCenterSeeder::class,
            HallSeeder::class,
            CourseSeeder::class,
            SessionSeeder::class,
            EnrollmentSeeder::class,
            PaymentSeeder::class,
            InvoiceSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
