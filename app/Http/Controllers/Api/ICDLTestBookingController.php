<?php

namespace App\Http\Controllers\Api;

use App\Models\ICDLTest;
use App\Models\ICDLTestBooking;
use App\Models\TrainingCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ICDLTestBookingController extends BaseController
{
    /**
     * Store a newly created ICDL test booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'training_center_id' => 'required|exists:training_centers,id',
            'booking_time' => 'required|date_format:h:i A',
            'booking_date' => 'required|date_format:Y-m-d',
            'test_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the training center exists and is valid
        $trainingCenter = TrainingCenter::findOrFail($request->training_center_id);

        $icdlTest = $trainingCenter->icdlTests()->first();

        // Combine booking date and time
        $bookingDateTime = Carbon::createFromFormat(
            'Y-m-d h:i A',
            $request->booking_date . ' ' . $request->booking_time
        );

        // Validate booking time (must be between 12 PM and 6 PM)
        $hour = (int) $bookingDateTime->format('H');
        if ($hour < 12 || $hour >= 18) {
            return response()->json([
                'errors' => ['booking_time' => ['Booking time must be between 12 PM and 6 PM']]
            ], 422);
        }

        // Check if the time slot is available (less than 5 bookings)
        $bookingsCount = ICDLTestBooking::where('training_center_id', $trainingCenter->id)
            ->whereDate('booking_time', $bookingDateTime->format('Y-m-d'))
            ->whereRaw("HOUR(booking_time) = ?", [$hour])
            ->count();

        if ($bookingsCount >= 5) {
            return response()->json([
                'errors' => ['booking_time' => ['This time slot is no longer available']]
            ], 422);
        }

        // Create the booking
        $booking = ICDLTestBooking::create([
            'user_id' => $user->id,
            'i_c_d_l_test_id' => $icdlTest->id,
            'training_center_id' => $trainingCenter->id,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'total_price' => $icdlTest->price,
            'notes' => $request->notes,
            'booking_time' => $bookingDateTime,
            'test_type' => $request->test_type,
        ]);

        return response()->json([
            'message' => 'ICDL test booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    /**
     * Get available times for ICDL test bookings.
     *
     * @param  \App\Models\TrainingCenter  $trainingCenter
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTimes(TrainingCenter $trainingCenter, Request $request)
    {
        // Debug request
        Log::info('Request data:', $request->all());

        $trainingCenterId = $trainingCenter->id;

        // Get dates from tomorrow to 10 days ahead
        $tomorrow = Carbon::tomorrow()->startOfDay();
        $tenDaysLater = Carbon::tomorrow()->addDays(9)->endOfDay();

        // Hours from 12 PM to 6 PM
        $availableHours = range(12, 17);

        $availableTimes = [];

        // Loop through each day
        for ($date = clone $tomorrow; $date <= $tenDaysLater; $date->addDay()) {
            $dayTimes = [];

            // Loop through each hour
            foreach ($availableHours as $hour) {
                // Check if this time slot has less than 5 bookings
                $bookingsCount = ICDLTestBooking::where('training_center_id', $trainingCenterId)
                    ->whereDate('booking_time', $date->format('Y-m-d'))
                    ->whereRaw("HOUR(booking_time) = ?", [$hour])
                    ->count();

                if ($bookingsCount < 5) {
                    // Format time as 12:00 PM, 1:00 PM, etc.
                    $formattedHour = $hour == 12 ? '12:00 PM' : ($hour - 12) . ':00 PM';

                    $dayTimes[] = [
                        'time' => $formattedHour,
                        'timestamp' => Carbon::create($date->year, $date->month, $date->day, $hour, 0, 0)->format('Y-m-d H:i:s'),
                        'available_slots' => 5 - $bookingsCount
                    ];
                }
            }

            // Only add days that have available times
            if (!empty($dayTimes)) {
                $availableTimes[] = [
                    'date' => $date->format('Y-m-d'),
                    'formatted_date' => $date->format('l, F j, Y'),
                    'times' => $dayTimes
                ];
            }
        }

        return response()->json([
            'available_times' => $availableTimes,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get user's bookings with filters
        $bookings = ICDLTestBooking::where('user_id', $user->id);

        // Apply filters if provided
        if ($request->has('status')) {
            $bookings->where('booking_status', $request->status);
        }

        // Get the paginated results
        $bookings = $bookings->with(['icdlTest', 'trainingCenter'])
            ->latest()
            ->paginate($request->input('per_page', 10));

        return $this->sendResponse(
            $bookings,
            'ICDL test bookings retrieved successfully'
        );
    }
}
