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
        if ($request->has('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        if ($request->has('max_capacity')) {
            $query->where('capacity', '<=', $request->max_capacity);
        }

        // Filter by date and time availability (check existing bookings)
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

                    // Exclude halls where there is a booking that overlaps with the requested time
                    $query->where(function ($query) use ($startTimeObj, $endTimeObj) {
                        // Booking starts during the requested period
                        $query->whereTime('start_time', '>=', $startTimeObj->format('H:i:s'))
                            ->whereTime('start_time', '<', $endTimeObj->format('H:i:s'));
                    })->orWhere(function ($query) use ($startTimeObj, $endTimeObj) {
                        // Booking ends during the requested period
                        $query->whereTime('end_time', '>', $startTimeObj->format('H:i:s'))
                            ->whereTime('end_time', '<=', $endTimeObj->format('H:i:s'));
                    })->orWhere(function ($query) use ($startTimeObj, $endTimeObj) {
                        // Booking completely encompasses the requested period
                        $query->whereTime('start_time', '<=', $startTimeObj->format('H:i:s'))
                            ->whereTime('end_time', '>=', $endTimeObj->format('H:i:s'));
                    });
                });
            });
        }

        $halls = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            HallResource::collection($halls),
            'Halls for training center retrieved successfully'
        );
    }
}
