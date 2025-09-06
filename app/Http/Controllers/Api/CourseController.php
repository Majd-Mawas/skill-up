<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Course\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::with(['category', 'trainingCenters'])
            ->withCount(['enrollments', 'reviews']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by category
        // if ($request->has('category')) {
        //     $query->byCategory($request->category);
        // }

        // // Filter by difficulty level
        // if ($request->has('difficulty')) {
        //     $query->where('difficulty_level', $request->difficulty);
        // }

        // // Filter by active status
        // if ($request->has('active')) {
        //     $query->where('is_active', $request->boolean('active'));
        // }

        // // Only show active courses by default
        // if (!$request->has('include_inactive')) {
        //     $query->active();
        // }

        $courses = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            CourseResource::collection($courses),
            'Courses retrieved successfully'
        );
    }
    
    /**
     * Display a listing of online courses provided by trainers.
     */
    public function trainerOnlineCourses(Request $request): JsonResponse
    {
        $query = Course::with(['category', 'trainers'])
            ->withCount(['enrollments', 'reviews'])
            ->where('is_online', true)
            ->whereHas('trainers');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }
        
        // Filter by trainer
        if ($request->has('trainer_id')) {
            $query->whereHas('trainers', function($q) use ($request) {
                $q->where('users.id', $request->trainer_id);
            });
        }

        $courses = $query->paginate($request->get('per_page', 15));

        return $this->successResponse(
            CourseResource::collection($courses),
            'Trainer online courses retrieved successfully'
        );
    }

    /**
     * Store a newly created course.
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        try {
            $course = Course::create($request->validated());

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $course->addMediaFromRequest('thumbnail')
                    ->toMediaCollection('thumbnail');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $course->addMedia($file)
                        ->toMediaCollection('gallery');
                }
            }

            $course->load(['category', 'trainingCenters']);

            return $this->successResponse(
                new CourseResource($course),
                'Course created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create course: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): JsonResponse
    {
        $course->load(['category', 'trainingCenters', 'levels', 'reviews'])
            ->loadCount(['enrollments', 'reviews']);

        return $this->successResponse(
            new CourseResource($course),
            'Course retrieved successfully'
        );
    }

    /**
     * Update the specified course.
     */
    public function update(StoreCourseRequest $request, Course $course): JsonResponse
    {
        try {
            $course->update($request->validated());

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $course->clearMediaCollection('thumbnail');
                $course->addMediaFromRequest('thumbnail')
                    ->toMediaCollection('thumbnail');
            }

            // Handle gallery uploads
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $course->addMedia($file)
                        ->toMediaCollection('gallery');
                }
            }

            $course->load(['category', 'trainingCenters']);

            return $this->successResponse(
                new CourseResource($course),
                'Course updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update course: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): JsonResponse
    {
        try {
            $course->clearMediaCollection('thumbnail');
            $course->clearMediaCollection('gallery');
            $course->clearMediaCollection('materials');
            $course->delete();

            return $this->successResponse(
                null,
                'Course deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete course: ' . $e->getMessage(),
                500
            );
        }
    }
}
