<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Hall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Create booking with all required fields
        $booking = new Booking($validatedData);
        $booking->user_id = Auth::id();
        $booking->status = 'pending'; // Default status
        $booking->legacy_date = $validatedData['legacy_date'];
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

        // Update booking
        $booking->update($request->validated());

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
}
