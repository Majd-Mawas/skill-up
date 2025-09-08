<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreOnlineCourseBookingRequest;
use App\Http\Requests\UpdateOnlineCourseBookingRequest;
use App\Models\Course;
use App\Models\OnlineCourseBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class OnlineCourseBookingController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OnlineCourseBooking::with(['course'])
            ->where('user_id', Auth::id())
            ->latest();

        // Apply filters if provided
        if ($request->has('booking_status') && $request->booking_status) {
            $query->where('booking_status', $request->booking_status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('filter_start_date') && $request->filter_start_date) {
            $query->whereDate('start_date', '>=', $request->filter_start_date);
        }

        $bookings = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse([
            'bookings' => $bookings->items(),
            'pagination' => [
                'total' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
            ],
        ], 'Online course bookings retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOnlineCourseBookingRequest $request)
    {
        // Verify course is online
        $course = Course::findOrFail($request->course_id);

        if (!$course->is_online) {
            return $this->sendError('This course is not an online course.', [], 422);
        }

        // Get price from course_trainer pivot table using the specified trainer
        $courseTrainer = $course->trainers()->where('user_id', $request->trainer_id)->first();

        if (!$courseTrainer) {
            return $this->sendError('The specified trainer is not assigned to this online course.', [], 422);
        }

        $totalPrice = $courseTrainer->pivot->price ?? 0;
        $startDate = $courseTrainer->pivot->start_date;

        // Create the booking
        $booking = OnlineCourseBooking::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'trainer_id' => $request->trainer_id,
            'start_date' => $startDate,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        return $this->sendResponse($booking, 'Online course booking created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = OnlineCourseBooking::with(['course', 'user'])->findOrFail($id);

        // Check authorization
        if (!Gate::allows('view', $booking)) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        return $this->sendResponse($booking, 'Online course booking retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOnlineCourseBookingRequest $request, string $id)
    {
        $booking = OnlineCourseBooking::findOrFail($id);

        // Authorization is handled in the form request

        // Only allow updates if booking is still pending
        if ($booking->booking_status !== 'pending') {
            return $this->sendError('Cannot update a booking that is not in pending status.', [], 422);
        }

        // Update the booking
        if ($request->has('start_date')) {
            $booking->start_date = $request->start_date;
        }

        if ($request->has('notes')) {
            $booking->notes = $request->notes;
        }

        $booking->save();

        return $this->sendResponse($booking, 'Online course booking updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = OnlineCourseBooking::findOrFail($id);

        // Check authorization
        if (!Gate::allows('delete', $booking)) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        // Update booking status to cancelled
        $booking->booking_status = 'cancelled';
        $booking->save();

        return $this->sendResponse(null, 'Online course booking cancelled successfully');
    }

    /**
     * Get available online courses for booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableOnlineCourses(Request $request)
    {
        $query = Course::where('is_online', true)
            ->with(['category', 'trainers'])
            ->whereHas('trainers');

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Apply category filter if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $courses = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse([
            'courses' => $courses->items(),
            'pagination' => [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
            ],
        ], 'Available online courses retrieved successfully');
    }

    /**
     * Display a listing of both current and finished online course bookings for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function currentAndFinishedCourses(Request $request)
    {
        // Get current courses
        $currentQuery = OnlineCourseBooking::with(['course', 'user'])
            ->where('user_id', Auth::id())
            ->where('booking_status', 'confirmed')
            ->whereDate('start_date', '<=', now())
            ->latest();

        $currentBookings = $currentQuery->get();

        // Get finished courses
        $finishedQuery = OnlineCourseBooking::with(['course', 'user'])
            ->where('user_id', Auth::id())
            ->where(function($query) {
                $query->where('booking_status', 'completed')
                      ->orWhere(function($q) {
                          // Consider courses as finished if they started more than course duration ago
                          $q->where('booking_status', 'confirmed')
                            ->whereHas('course', function($courseQuery) {
                                $courseQuery->whereRaw('DATE_ADD(online_course_bookings.start_date, INTERVAL courses.duration_hours HOUR) < NOW()');
                            });
                      });
            })
            ->latest();

        $finishedBookings = $finishedQuery->get();

        return $this->sendResponse([
            'current_bookings' => $currentBookings,
            'finished_bookings' => $finishedBookings
        ], 'Current and finished online course bookings retrieved successfully');
    }
}
