<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlacementTestBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlacementTestBookingController extends BaseController
{
    /**
     * Display a listing of the placement test bookings for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get user's placement test bookings with relationships
        $bookings = PlacementTestBooking::where('user_id', $user->id)
            ->with(['placementTest', 'booking'])
            ->get();

        // Group bookings by placement test type
        $groupedBookings = [
            'English' => [],
            'French' => [],
            'Turkish' => [],
            'Deutsch' => []
        ];

        foreach ($bookings as $booking) {
            $testName = $booking->placementTest->name;
            
            if (str_contains($testName, 'English')) {
                $groupedBookings['English'][] = $booking;
            } elseif (str_contains($testName, 'French')) {
                $groupedBookings['French'][] = $booking;
            } elseif (str_contains($testName, 'Turkish')) {
                $groupedBookings['Turkish'][] = $booking;
            } elseif (str_contains($testName, 'Deutsch')) {
                $groupedBookings['Deutsch'][] = $booking;
            }
        }

        return $this->sendResponse(
            $groupedBookings,
            'Placement test bookings retrieved successfully'
        );
    }
}