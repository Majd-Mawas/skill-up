<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Institute\StoreInstituteRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\HallResource;
use App\Http\Resources\TrainingCenterResource;
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
            ->with(['category'])
            ->withCount(['enrollments', 'reviews']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
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
            'Courses for training center retrieved successfully'
        );
    }

    public function halls(TrainingCenter $trainingCenter, Request $request): JsonResponse
    {
        $query = $trainingCenter->halls();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
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
        if ($request->has('date') && $request->has('start_time') && $request->has('end_time')) {
            $date = $request->date;
            $startTime = $request->start_time;
            $endTime = $request->end_time;

            // Exclude halls with direct bookings
            $query->whereDoesntHave('bookings', function ($query) use ($date, $startTime, $endTime) {
                $query->where('date', $date)
                    ->where(function ($query) use ($startTime, $endTime) {
                        // Exclude halls where there is a booking that overlaps with the requested time
                        $query->where(function ($query) use ($startTime, $endTime) {
                            // Booking starts during the requested period
                            $query->where('start_time', '>=', $startTime)
                                ->where('start_time', '<', $endTime);
                        })->orWhere(function ($query) use ($startTime, $endTime) {
                            // Booking ends during the requested period
                            $query->where('end_time', '>', $startTime)
                                ->where('end_time', '<=', $endTime);
                        })->orWhere(function ($query) use ($startTime, $endTime) {
                            // Booking completely encompasses the requested period
                            $query->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                    });
            });

            // Exclude halls with sessions from courses that have active invoices
            $query->whereDoesntHave('sessions', function ($query) use ($date, $startTime, $endTime) {
                $query->whereHas('course', function ($courseQuery) {
                    $courseQuery->whereHas('invoices', function ($invoiceQuery) {
                        $invoiceQuery->where('status', '!=', 'cancelled');
                    });
                })->where(function ($sessionQuery) use ($date, $startTime, $endTime) {
                    // Convert date and times to datetime for comparison with session start/end times
                    $requestStartDateTime = date('Y-m-d', strtotime($date)) . ' ' . $startTime;
                    $requestEndDateTime = date('Y-m-d', strtotime($date)) . ' ' . $endTime;

                    $sessionQuery->where(function ($query) use ($requestStartDateTime, $requestEndDateTime) {
                        // Session starts during the requested period
                        $query->where('start_time', '>=', $requestStartDateTime)
                            ->where('start_time', '<', $requestEndDateTime);
                    })->orWhere(function ($query) use ($requestStartDateTime, $requestEndDateTime) {
                        // Session ends during the requested period
                        $query->where('end_time', '>', $requestStartDateTime)
                            ->where('end_time', '<=', $requestEndDateTime);
                    })->orWhere(function ($query) use ($requestStartDateTime, $requestEndDateTime) {
                        // Session completely encompasses the requested period
                        $query->where('start_time', '<=', $requestStartDateTime)
                            ->where('end_time', '>=', $requestEndDateTime);
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
