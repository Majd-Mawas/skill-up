<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\ICDLCard;
use App\Models\ICDLCardBooking;
use App\Models\TrainingCenter;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICDLCardBookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the ICDL card booking functionality.
     *
     * @return void
     */
    public function test_icdl_card_booking_functionality()
    {
        // Create an area
        $area = Area::create([
            'name' => 'Test Area',
            'description' => 'Test Area Description',
        ]);
        
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '+963931234567',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'phone_verified' => true,
            'remember_token' => Str::random(10),
            'area_id' => $area->id,
        ]);
        
        // Create a training center
        $trainingCenter = TrainingCenter::create([
            'name' => 'Test Training Center',
            'address' => '123 Test Street',
            'phone_number' => '+963931234567',
            'email' => 'center@example.com',
            'area_id' => $area->id,
        ]);
        
        // Create an ICDL card
        $icdlCard = ICDLCard::create([
            'name' => 'ICDL Basic Card',
            'description' => 'Basic ICDL certification card',
            'price' => 100.00,
            'training_center_id' => $trainingCenter->id,
            'is_active' => true,
        ]);
        
        // Create a booking directly in the database
        $booking = ICDLCardBooking::create([
            'user_id' => $user->id,
            'i_c_d_l_card_id' => $icdlCard->id,
            'training_center_id' => $trainingCenter->id,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'total_price' => $icdlCard->price,
            'notes' => 'Test booking notes',
        ]);
        
        // Assert the booking was created in the database
        $this->assertDatabaseHas('i_c_d_l_card_bookings', [
            'user_id' => $user->id,
            'i_c_d_l_card_id' => $icdlCard->id,
            'training_center_id' => $trainingCenter->id,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'total_price' => $icdlCard->price,
            'notes' => 'Test booking notes',
        ]);
        
        // Assert the relationship works
        $this->assertEquals($user->id, $booking->user_id);
        $this->assertEquals($icdlCard->id, $booking->i_c_d_l_card_id);
        $this->assertEquals($trainingCenter->id, $booking->training_center_id);
    }
}