<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Hall;
use App\Models\HallBooking;
use App\Models\PlacementTest;
use App\Models\PlacementTestBooking;
use App\Models\TrainingCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends BaseController
{
    /**
     * Display a listing of the bookings for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Booking::with(['hall', 'hall.media'])
            ->where('user_id', Auth::id())
            ->latest();

        // Apply filters if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('filter_start_date') && $request->filter_start_date) {
            $query->where(function ($q) use ($request) {
                // Find bookings where start_date or end_date falls within the filter range
                $q->whereDate('start_date', '>=', $request->filter_start_date)
                    ->orWhereDate('end_date', '>=', $request->filter_start_date);
            });
        }

        if ($request->has('filter_end_date') && $request->filter_end_date) {
            $query->where(function ($q) use ($request) {
                // Find bookings where start_date or end_date falls within the filter range
                $q->whereDate('start_date', '<=', $request->filter_end_date)
                    ->orWhereDate('end_date', '<=', $request->filter_end_date);
            });
        }

        $bookings = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse(
            BookingResource::collection($bookings),
            'Bookings retrieved successfully',
            $bookings
        );
    }

    /**
     * Store a newly created booking in storage.
     *
     * @param  \App\Http\Requests\StoreBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookingRequest $request)
    {
        // Validate hall exists and is available
        $hall = Hall::findOrFail($request->hall_id);

        if (!$hall->available) {
            return $this->sendError('Hall is not available for booking.', [], 422);
        }

        // Get validated data
        $validatedData = $request->validated();

        // Format legacy_date from start_date
        $validatedData['legacy_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['start_date'])->format('Y-m-d');

        // Calculate total price
        $hall = Hall::findOrFail($validatedData['hall_id']);
        $startDateTime = Carbon::parse($validatedData['start_date'] . ' ' . $validatedData['start_time']);
        $endDateTime = Carbon::parse($validatedData['end_date'] . ' ' . $validatedData['end_time']);
        $durationInHours = $endDateTime->diffInMinutes($startDateTime) / 60;
        $totalPrice = $hall->price_per_hour * $durationInHours;

        // Create booking with all required fields
        $booking = new Booking($validatedData);
        $booking->user_id = Auth::id();
        $booking->status = 'pending'; // Default status
        $booking->legacy_date = $validatedData['legacy_date'];
        $booking->total_price = $totalPrice;
        $booking->save();

        return $this->sendResponse(
            new BookingResource($booking),
            'Booking created successfully',
            null,
            201
        );
    }

    /**
     * Display the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        $booking->load(['hall', 'hall.media']);

        return $this->sendResponse(
            new BookingResource($booking),
            'Booking retrieved successfully'
        );
    }

    /**
     * Update the specified booking in storage.
     *
     * @param  \App\Http\Requests\UpdateBookingRequest  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        // Check if booking is already cancelled
        if ($booking->status === 'cancelled') {
            return $this->sendError('Cannot update a cancelled booking.', [], 422);
        }

        $validatedData = $request->validated();

        // Recalculate total price if dates or times changed
        if (
            isset($validatedData['start_date']) || isset($validatedData['end_date']) ||
            isset($validatedData['start_time']) || isset($validatedData['end_time'])
        ) {

            $hall = Hall::findOrFail($booking->hall_id);
            $startDate = isset($validatedData['start_date']) ?
                Carbon::createFromFormat('d/m/Y', $validatedData['start_date'])->format('Y-m-d') :
                $booking->start_date->format('Y-m-d');
            $endDate = isset($validatedData['end_date']) ?
                Carbon::createFromFormat('d/m/Y', $validatedData['end_date'])->format('Y-m-d') :
                $booking->end_date->format('Y-m-d');
            $startTime = isset($validatedData['start_time']) ? $validatedData['start_time'] : $booking->start_time->format('H:i');
            $endTime = isset($validatedData['end_time']) ? $validatedData['end_time'] : $booking->end_time->format('H:i');

            $startDateTime = Carbon::parse($startDate . ' ' . $startTime);
            $endDateTime = Carbon::parse($endDate . ' ' . $endTime);
            $durationInHours = $endDateTime->diffInMinutes($startDateTime) / 60;
            $validatedData['total_price'] = $hall->price_per_hour * $durationInHours;
        }

        // Update booking
        $booking->update($validatedData);

        return $this->sendResponse(
            new BookingResource($booking),
            'Booking updated successfully'
        );
    }

    /**
     * Remove the specified booking from storage (cancel booking).
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        // Check if booking is already cancelled
        if ($booking->status === 'cancelled') {
            return $this->sendError('Booking is already cancelled.', [], 422);
        }

        // Check if booking is within 24 hours
        $bookingDateTime = Carbon::parse($booking->start_date . ' ' . $booking->start_time);
        $now = Carbon::now();

        if ($bookingDateTime->diffInHours($now) < 24) {
            return $this->sendError(
                'Bookings can only be cancelled at least 24 hours in advance.',
                [],
                422
            );
        }

        // Cancel booking
        $booking->status = 'cancelled';
        $booking->save();

        return $this->sendResponse(
            new BookingResource($booking),
            'Booking cancelled successfully'
        );
    }

    /**
     * Create a hall booking for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createHallBooking(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'hall_id' => 'required|exists:halls,id',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // Validate hall exists and is available
        $hall = Hall::findOrFail($request->hall_id);

        if (!$hall->available) {
            return $this->sendError('Hall is not available for booking.', [], 422);
        }

        // Check hall availability (no overlapping bookings)
        $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        $overlappingBookings = HallBooking::where('hall_id', $request->hall_id)
            ->where(function ($query) use ($startDate, $endDate, $request) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Check if booking dates overlap
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                            $q2->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })->where(function ($q) use ($request) {
                    // Check if booking times overlap
                    $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($q2) use ($request) {
                            $q2->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($overlappingBookings) {
            return $this->sendError('Hall is not available for the selected date and time.', [], 422);
        }

        // Calculate total price
        $startDateTime = Carbon::parse($startDate . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($endDate . ' ' . $request->end_time);
        $durationInHours = $endDateTime->diffInMinutes($startDateTime) / 60;
        $totalPrice = $hall->price_per_hour * $durationInHours;

        // Create hall booking
        $hallBooking = new HallBooking([
            'hall_id' => $request->hall_id,
            'user_id' => Auth::id(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $totalPrice,
            'status' => 'pending', // Default status
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
        ]);

        $hallBooking->save();

        return $this->sendResponse(
            new BookingResource($hallBooking),
            'Hall booking created successfully',
            null,
            201
        );
    }

    /**
     * Create a new placement test booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPlacementTestBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_center_id' => 'required|exists:training_centers,id',
            'booking_time' => 'required|date_format:h:i A',
            'booking_date' => 'required|date_format:Y-m-d',
            'test_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // Find the placement test by training center and name with case-insensitive search
        $placementTest = PlacementTest::where('training_center_id', $request->training_center_id)
            ->where(function($query) use ($request) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->test_name) . '%'])
                      ->orWhereRaw('LOWER(name) = ?', [strtolower($request->test_name)]);
            })
            ->first();

        if (!$placementTest) {
            return $this->sendError(
                'Placement test not found for the specified training center and test name.',
                [],
                404
            );
        }

        // Parse booking date and time
        $bookingDateTime = Carbon::createFromFormat(
            'Y-m-d h:i A',
            $request->booking_date . ' ' . $request->booking_time
        );


        // Create the placement test booking
        $placementTestBooking = PlacementTestBooking::create([
            'placement_test_id' => $placementTest->id,
            'booking_time' => $bookingDateTime,
            'user_id' => Auth::id(),
        ]);

        return $this->sendResponse(
            [
                'id' => $placementTestBooking->id,
                'test_name' => $placementTest->name,
                'booking_time' => $placementTestBooking->booking_time->format('h:i A'),
                'booking_date' => $placementTestBooking->booking_time->format('Y-m-d'),
            ],
            'Placement test booking created successfully',
            null,
            201
        );
    }
}
