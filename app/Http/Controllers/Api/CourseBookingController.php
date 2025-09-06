<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreCourseBookingRequest;
use App\Http\Requests\UpdateCourseBookingRequest;
use App\Models\Course;
use App\Models\CourseBooking;
use App\Models\TrainingCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CourseBookingController extends BaseController
{
    /**
     * Display a listing of the course bookings for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CourseBooking::with(['course', 'trainingCenter'])
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
        ], 'Course bookings retrieved successfully');
    }

    /**
     * Store a newly created course booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseBookingRequest $request)
    {
        // Verify course is offered at the training center
        $course = Course::findOrFail($request->course_id);
        $trainingCenter = TrainingCenter::findOrFail($request->training_center_id);

        $coursePivot = $course->trainingCenters()
            ->where('training_center_id', $trainingCenter->id)
            ->first();
        if (!$coursePivot) {
            return $this->sendError('This course is not offered at the selected training center.', [], 422);
        }

        // Get price from pivot table
        $totalPrice = $coursePivot->pivot->price;
        $startDate = $coursePivot->pivot->start_date;

        // Create the booking
        $booking = CourseBooking::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'training_center_id' => $request->training_center_id,
            'start_date' => $startDate,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        return $this->sendResponse($booking, 'Course booking created successfully');
    }
    /**
     * Display the specified course booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = CourseBooking::with(['course', 'trainingCenter', 'user'])->findOrFail($id);

        // Check authorization
        if (!Gate::allows('view', $booking)) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        return $this->sendResponse($booking, 'Course booking retrieved successfully');
    }

    /**
     * Update the specified course booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseBookingRequest $request, $id)
    {
        $booking = CourseBooking::findOrFail($id);

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

        return $this->sendResponse($booking, 'Course booking updated successfully');
    }

    /**
     * Cancel the specified course booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = CourseBooking::findOrFail($id);

        // Check authorization
        if (!Gate::allows('delete', $booking)) {
            return $this->sendError('Unauthorized.', [], 403);
        }

        // Update booking status to cancelled
        $booking->booking_status = 'cancelled';
        $booking->save();

        return $this->sendResponse(null, 'Course booking cancelled successfully');
    }

    /**
     * Get available courses for booking at a specific training center.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableCourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_center_id' => 'required|exists:training_centers,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $trainingCenter = TrainingCenter::findOrFail($request->training_center_id);
        $courses = $trainingCenter->courses()->with('category')->get();

        return $this->sendResponse($courses, 'Available courses retrieved successfully');
    }
}
