<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\User;
use App\Models\HallBooking;
use Illuminate\Database\Seeder;

class HallBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have halls and users to work with
        if (Hall::count() === 0) {
            $this->command->info('No halls found. Skipping hall bookings seeding.');
            return;
        }

        if (User::count() === 0) {
            $this->command->info('No users found. Skipping hall bookings seeding.');
            return;
        }

        // Create 20 hall bookings
        HallBooking::factory()->count(20)->create();

        // Create 5 confirmed bookings
        HallBooking::factory()->count(5)->confirmed()->create();

        // Create 5 pending bookings
        HallBooking::factory()->count(5)->pending()->create();

        // Create 5 cancelled bookings
        HallBooking::factory()->count(5)->cancelled()->create();

        $this->command->info('Hall bookings seeded successfully.');
    }
}