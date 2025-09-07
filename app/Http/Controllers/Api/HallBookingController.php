<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookingResource;
use App\Http\Resources\HallBookingResource;
use App\Models\Hall;
use App\Models\HallBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HallBookingController extends BaseController
{
    /**
     * Display a listing of the hall bookings for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = HallBooking::with(['hall', 'hall.media'])
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

        $hallBookings = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse(
            HallBookingResource::collection($hallBookings),
            'Hall bookings retrieved successfully',
            $hallBookings
        );
    }

    /**
     * Store a newly created hall booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hall_id' => ['required', 'exists:halls,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // Validate hall exists and is available
        $hall = Hall::findOrFail($request->hall_id);

        if (!$hall->available) {
            return $this->sendError('Hall is not available for booking.', [], 422);
        }

        // Check for overlapping bookings
        $this->validateHallAvailability($request, $validator);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // Calculate total price
        $startDateTime = Carbon::parse($request->start_date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->end_date . ' ' . $request->end_time);
        $durationInHours = $endDateTime->diffInMinutes($startDateTime) / 60;
        $totalPrice = $hall->price_per_hour * $durationInHours;

        // Create booking
        $hallBooking = new HallBooking([
            'hall_id' => $request->hall_id,
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $totalPrice,
            'status' => 'pending', // Default status
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
     * Display the specified hall booking.
     *
     * @param  \App\Models\HallBooking  $hallBooking
     * @return \Illuminate\Http\Response
     */
    public function show(HallBooking $hallBooking)
    {
        // Check if the booking belongs to the authenticated user
        if ($hallBooking->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        $hallBooking->load(['hall', 'hall.media']);

        return $this->sendResponse(
            new HallBookingResource($hallBooking),
            'Hall booking retrieved successfully'
        );
    }

    /**
     * Update the specified hall booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HallBooking  $hallBooking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HallBooking $hallBooking)
    {
        // Check if the booking belongs to the authenticated user
        if ($hallBooking->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        // Check if booking is already cancelled
        if ($hallBooking->status === 'cancelled') {
            return $this->sendError('Cannot update a cancelled booking.', [], 422);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => ['sometimes', 'date', 'after_or_equal:today'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
            'status' => ['sometimes', 'in:pending,confirmed,cancelled'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // If dates or times are being updated, check for overlapping bookings
        if ($request->has('start_date') || $request->has('end_date') ||
            $request->has('start_time') || $request->has('end_time')) {
            $this->validateHallAvailability($request, $validator, $hallBooking->id);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }

            // Recalculate total price if dates or times changed
            $hall = Hall::findOrFail($hallBooking->hall_id);
            $startDateTime = Carbon::parse(
                ($request->start_date ?? $hallBooking->start_date) . ' ' .
                ($request->start_time ?? $hallBooking->start_time->format('H:i'))
            );
            $endDateTime = Carbon::parse(
                ($request->end_date ?? $hallBooking->end_date) . ' ' .
                ($request->end_time ?? $hallBooking->end_time->format('H:i'))
            );
            $durationInHours = $endDateTime->diffInMinutes($startDateTime) / 60;
            $totalPrice = $hall->price_per_hour * $durationInHours;

            $hallBooking->total_price = $totalPrice;
        }

        // Update booking
        $hallBooking->fill($request->only([
            'start_date', 'end_date', 'start_time', 'end_time', 'status'
        ]));
        $hallBooking->save();

        return $this->sendResponse(
            new HallBookingResource($hallBooking),
            'Hall booking updated successfully'
        );
    }

    /**
     * Remove the specified hall booking from storage (cancel booking).
     *
     * @param  \App\Models\HallBooking  $hallBooking
     * @return \Illuminate\Http\Response
     */
    public function destroy(HallBooking $hallBooking)
    {
        // Check if the booking belongs to the authenticated user
        if ($hallBooking->user_id !== Auth::id()) {
            return $this->sendError('Unauthorized access.', [], 403);
        }

        // Check if booking is already cancelled
        if ($hallBooking->status === 'cancelled') {
            return $this->sendError('Booking is already cancelled.', [], 422);
        }

        // Check if booking is within 24 hours
        $bookingDateTime = Carbon::parse($hallBooking->start_date . ' ' . $hallBooking->start_time);
        $now = Carbon::now();

        if ($bookingDateTime->diffInHours($now) < 24) {
            return $this->sendError(
                'Bookings can only be cancelled at least 24 hours in advance.',
                [],
                422
            );
        }

        // Cancel booking
        $hallBooking->status = 'cancelled';
        $hallBooking->save();

        return $this->sendResponse(
            new HallBookingResource($hallBooking),
            'Hall booking cancelled successfully'
        );
    }

    /**
     * Validate that the hall is available for the requested time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  int|null  $excludeBookingId
     * @return void
     */
    protected function validateHallAvailability($request, $validator, $excludeBookingId = null)
    {
        $hallId = $request->input('hall_id', null);
        $startDate = $request->input('start_date', null);
        $endDate = $request->input('end_date', null);
        $startTime = $request->input('start_time', null);
        $endTime = $request->input('end_time', null);

        // If updating an existing booking, use current values for any missing fields
        if ($excludeBookingId) {
            $existingBooking = HallBooking::findOrFail($excludeBookingId);
            $hallId = $hallId ?? $existingBooking->hall_id;
            $startDate = $startDate ?? $existingBooking->start_date->format('Y-m-d');
            $endDate = $endDate ?? $existingBooking->end_date->format('Y-m-d');
            $startTime = $startTime ?? $existingBooking->start_time->format('H:i');
            $endTime = $endTime ?? $existingBooking->end_time->format('H:i');
        }

        // Check for overlapping bookings across the date range
        $query = HallBooking::where('hall_id', $hallId)
            ->where('status', '!=', 'cancelled');

        // Exclude the current booking if updating
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $overlappingBookings = $query->where(function ($query) use ($startDate, $endDate) {
                // Bookings that overlap with our date range
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Existing booking starts during our date range
                    $q->where('start_date', '>=', $startDate)
                      ->where('start_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Existing booking ends during our date range
                    $q->where('end_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Existing booking spans our entire date range
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $endDate);
                });
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // New booking starts during an existing booking
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New booking ends during an existing booking
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New booking contains an existing booking
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            })
            ->exists();

        if ($overlappingBookings) {
            $validator->errors()->add('hall_id', 'The hall is already booked for the selected time period.');
        }
    }
}
