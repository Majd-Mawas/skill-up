<?php

namespace Database\Seeders;

use App\Models\ICDLCard;
use App\Models\ICDLCardBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ICDLCardBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users, ICDL cards
        $users = User::all();
        $icdlCards = ICDLCard::all();
        
        // Skip if no users or cards
        if ($users->isEmpty() || $icdlCards->isEmpty()) {
            return;
        }
        
        // Possible booking statuses
        $bookingStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        
        // Create 20 random bookings
        for ($i = 0; $i < 20; $i++) {
            // Get random user and card
            $user = $users->random();
            $icdlCard = $icdlCards->random();
            
            // Get the training center from the card
            $trainingCenterId = $icdlCard->training_center_id;
            
            // Generate a random booking time (between 12 PM and 5 PM)
            $bookingDate = Carbon::now()->addDays(rand(1, 30));
            $bookingHour = rand(12, 17);
            $bookingTime = Carbon::create(
                $bookingDate->year,
                $bookingDate->month,
                $bookingDate->day,
                $bookingHour,
                0,
                0
            );
            
            // Create the booking
            ICDLCardBooking::create([
                'user_id' => $user->id,
                'i_c_d_l_card_id' => $icdlCard->id,
                'training_center_id' => $trainingCenterId,
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'booking_status' => $bookingStatuses[array_rand($bookingStatuses)],
                'total_price' => $icdlCard->price,
                'notes' => fake()->optional(0.7)->sentence(),
                'booking_time' => $bookingTime,
            ]);
        }
    }
}