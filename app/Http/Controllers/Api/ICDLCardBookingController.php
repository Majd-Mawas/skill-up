<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ICDLCard;
use App\Models\ICDLCardBooking;
use App\Models\TrainingCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ICDLCardBookingController extends Controller
{
    /**
     * Store a newly created ICDL card booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            // 'i_c_d_l_card_id' => 'required|exists:i_c_d_l_cards,id',
            'training_center_id' => 'required|exists:training_centers,id',
            'notes' => 'nullable|string',
            'full_name_arabic' => 'required|string|max:255',
            'full_name_english' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'national_id' => 'required|string|max:20',
            'personal_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_front_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'id_back_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the authenticated user
        $user = Auth::user();

        $trainingCenter = TrainingCenter::findOrFail($request->training_center_id);

        $icdlCard = $trainingCenter->icdlCards()->first();

        // Create the booking
        $booking = ICDLCardBooking::create([
            'user_id' => $user->id,
            'i_c_d_l_card_id' => $icdlCard->id,
            'training_center_id' => $trainingCenter->id,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'total_price' => $icdlCard->price,
            'notes' => $request->notes,
            'booking_time' => now(),
            'full_name_arabic' => $request->full_name_arabic,
            'full_name_english' => $request->full_name_english,
            'birth_date' => $request->birth_date,
            'national_id' => $request->national_id,
        ]);

        // Add media files
        if ($request->hasFile('personal_photo')) {
            $booking->addMediaFromRequest('personal_photo')
                ->toMediaCollection('personal_photo');
        }

        if ($request->hasFile('id_front_photo')) {
            $booking->addMediaFromRequest('id_front_photo')
                ->toMediaCollection('id_front_photo');
        }

        if ($request->hasFile('id_back_photo')) {
            $booking->addMediaFromRequest('id_back_photo')
                ->toMediaCollection('id_back_photo');
        }

        return response()->json([
            'message' => 'ICDL card booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    /**
     * Get available times for ICDL card bookings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTimes(TrainingCenter $trainingCenter, Request $request)
    {
        // Debug request
        Log::info('Request data:', $request->all());

        // Validate request
        // $validator = Validator::make($request->all(), [
        //     'training_center_id' => 'required|exists:training_centers,id',
        // ]);

        // if ($validator->fails()) {
        //     Log::error('Validation failed:', $validator->errors()->toArray());
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

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
                $bookingsCount = ICDLCardBooking::where('training_center_id', $trainingCenterId)
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
        $bookings = ICDLCardBooking::where('user_id', $user->id);

        // Apply filters if provided
        if ($request->has('status')) {
            $bookings->where('booking_status', $request->status);
        }

        // Get the paginated results
        $bookings = $bookings->with(['icdlCard', 'trainingCenter'])
            ->latest()
            ->paginate(10);

        return response()->json($bookings);
    }
}
