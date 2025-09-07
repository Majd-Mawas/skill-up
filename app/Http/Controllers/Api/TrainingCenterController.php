<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Institute\StoreInstituteRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\HallResource;
use App\Http\Resources\TrainingCenterResource;
use App\Models\Course;
use App\Models\TrainingCenter;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingCenterController extends Controller
{
    /**
     * Display a listing of institutes.
     */
    public function index(Request $request): JsonResponse
    {
        $query = TrainingCenter::with(['area'])
            ->withCount(['courses', 'reviews']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by area
        if ($request->has('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $institutes = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            TrainingCenterResource::collection($institutes),
            'Institutes retrieved successfully'
        );
    }

    /**
     * Store a newly created institute.
     */
    public function store(StoreInstituteRequest $request): JsonResponse
    {
        try {
            $institute = TrainingCenter::create($request->validated());

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $institute->addMediaFromRequest('logo')
                    ->toMediaCollection('logo');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $institute->addMedia($file)
                        ->toMediaCollection('gallery');
                }
            }

            $institute->load(['area']);

            return $this->successResponse(
                new TrainingCenterResource($institute),
                'Institute created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create institute: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified institute.
     */
    public function show(TrainingCenter $trainingCenter): JsonResponse
    {
        $trainingCenter->load(['area', 'courses.category'])
            ->loadCount(['courses', 'reviews']);

        return $this->successResponse(
            new TrainingCenterResource($trainingCenter),
            'Institute retrieved successfully'
        );
    }

    /**
     * Update the specified institute.
     */
    public function update(StoreInstituteRequest $request, TrainingCenter $trainingCenter): JsonResponse
    {
        try {
            $trainingCenter->update($request->validated());

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $trainingCenter->clearMediaCollection('logo');
                $trainingCenter->addMediaFromRequest('logo')
                    ->toMediaCollection('logo');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $trainingCenter->addMedia($file)
                        ->toMediaCollection('gallery');
                }
            }

            $trainingCenter->load(['area']);

            return $this->successResponse(
                new TrainingCenterResource($trainingCenter),
                'Institute updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update institute: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified institute.
     */
    public function destroy(TrainingCenter $trainingCenter): JsonResponse
    {
        try {
            $trainingCenter->clearMediaCollection('logo');
            $trainingCenter->clearMediaCollection('gallery');
            $trainingCenter->delete();

            return $this->successResponse(
                null,
                'Institute deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete institute: ' . $e->getMessage(),
                500
            );
        }
    }

    public function courses(TrainingCenter $trainingCenter, Request $request): JsonResponse
    {
        $query = $trainingCenter->courses()
            ->where('is_online', false)
            ->with(['category'])
            ->withCount(['enrollments', 'reviews']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $courses = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            CourseResource::collection($courses),
            'Offline courses for training center retrieved successfully'
        );
    }

    public function onlineCourses(Request $request): JsonResponse
    {
        $query = Course::where('is_online', true)
            ->with(['category'])
            ->withCount(['enrollments', 'reviews']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $courses = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            CourseResource::collection($courses),
            'Online courses for training center retrieved successfully'
        );
    }

    public function halls(TrainingCenter $trainingCenter, Request $request): JsonResponse
    {
        $query = $trainingCenter->halls();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by availability
        if ($request->has('available')) {
            $query->where('available', $request->boolean('available'));
        }

        // Filter by capacity
        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        if ($request->filled('max_capacity')) {
            $query->where('capacity', '<=', $request->max_capacity);
        }

        // Filter by number of students
        if ($request->filled('students_count')) {
            $query->where('capacity', '>=', $request->students_count);
        }

        // Filter by date range availability (start_date and end_date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Exclude halls with bookings that overlap with the requested date range
            $query->whereDoesntHave('hallBookings', function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Booking starts during the requested period
                    $q->where('start_date', '>=', $startDate)
                      ->where('start_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Booking ends during the requested period
                    $q->where('end_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Booking completely encompasses the requested period
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $endDate);
                });
            });
        }

        // Filter by time range availability (from_time and to_time)
        if ($request->filled('from_time') && $request->filled('to_time')) {
            $fromTime = $request->from_time;
            $toTime = $request->to_time;

            // Exclude halls with bookings that overlap with the requested time range
            $query->whereDoesntHave('hallBookings', function ($query) use ($fromTime, $toTime) {
                // Convert string times to Carbon instances for proper comparison
                $fromTimeObj = \Carbon\Carbon::parse($fromTime);
                $toTimeObj = \Carbon\Carbon::parse($toTime);
                $fromTimeFormatted = $fromTimeObj->format('H:i:s');
                $toTimeFormatted = $toTimeObj->format('H:i:s');

                // Exclude halls where there is a booking that overlaps with the requested time
                $query->where(function ($query) use ($fromTimeFormatted, $toTimeFormatted) {
                    // Booking starts during the requested period
                    $query->where('start_time', '>=', $fromTimeFormatted)
                          ->where('start_time', '<', $toTimeFormatted);
                })->orWhere(function ($query) use ($fromTimeFormatted, $toTimeFormatted) {
                    // Booking ends during the requested period
                    $query->where('end_time', '>', $fromTimeFormatted)
                          ->where('end_time', '<=', $toTimeFormatted);
                })->orWhere(function ($query) use ($fromTimeFormatted, $toTimeFormatted) {
                    // Booking completely encompasses the requested period
                    $query->where('start_time', '<=', $fromTimeFormatted)
                          ->where('end_time', '>=', $toTimeFormatted);
                });
            });
        }

        // Filter by hours count (duration)
        if ($request->has('hours_count') && $request->hours_count > 0) {
            // This will be used in the frontend to calculate the booking duration
            // No direct filtering in the database as it depends on start_time and end_time
        }

        // Legacy filter by date and time availability (for backward compatibility)
        if ($request->filled('date') && $request->filled('start_time') && $request->filled('end_time')) {
            $date = $request->date;
            $startTime = $request->start_time;
            $endTime = $request->end_time;

            // Exclude halls with direct bookings
            $query->whereDoesntHave('hallBookings', function ($query) use ($date, $startTime, $endTime) {
                $query->where(function ($q) use ($date) {
                    // Check if the requested date falls within the booking's date range
                    $q->where('start_date', '<=', $date)
                        ->where('end_date', '>=', $date);
                })->where(function ($query) use ($startTime, $endTime) {
                    // Convert string times to Carbon instances for proper comparison
                    $startTimeObj = \Carbon\Carbon::parse($startTime);
                    $endTimeObj = \Carbon\Carbon::parse($endTime);
                    $startTimeFormatted = $startTimeObj->format('H:i:s');
                    $endTimeFormatted = $endTimeObj->format('H:i:s');

                    // Exclude halls where there is a booking that overlaps with the requested time
                    $query->where(function ($query) use ($startTimeFormatted, $endTimeFormatted) {
                        // Booking starts during the requested period
                        $query->where('start_time', '>=', $startTimeFormatted)
                              ->where('start_time', '<', $endTimeFormatted);
                    })->orWhere(function ($query) use ($startTimeFormatted, $endTimeFormatted) {
                        // Booking ends during the requested period
                        $query->where('end_time', '>', $startTimeFormatted)
                              ->where('end_time', '<=', $endTimeFormatted);
                    })->orWhere(function ($query) use ($startTimeFormatted, $endTimeFormatted) {
                        // Booking completely encompasses the requested period
                        $query->where('start_time', '<=', $startTimeFormatted)
                              ->where('end_time', '>=', $endTimeFormatted);
                    });
                });
            });
        }

        // // Filter by photo availability
        // if ($request->has('has_photo') && $request->boolean('has_photo')) {
        //     $query->whereHas('media', function ($q) {
        //         $q->where('collection_name', 'halls');
        //     });
        // }

        $halls = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            HallResource::collection($halls),
            'Halls for training center retrieved successfully'
        );
    }
}
