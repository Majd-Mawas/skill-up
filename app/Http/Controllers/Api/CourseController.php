<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Course\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\OnlineCourseResource;
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
     * Display a listing of popular online courses ordered by number of bookings.
     */
    private function popularOnlineCoursesMethod()
    {
        $query = Course::with(['category'])
            ->withCount(['onlineCourseBookings', 'reviews'])
            ->with(['trainers' => function ($query) {
                $query->withPivot('price', 'start_date', 'end_date'); // Load the price from pivot table
            }])
            ->where('is_online', true)
            ->whereHas('trainers')
            ->orderByDesc('online_course_bookings_count');

        return $query;
    }

    /**
     * Display a listing of popular online courses ordered by number of bookings.
     */
    public function popularOnlineCourses(Request $request): JsonResponse
    {
        $query = $this->popularOnlineCoursesMethod();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Limit results
        $limit = $request->get('limit', 10);
        $courses = $query->paginate($limit);

        return $this->successResponse(
            OnlineCourseResource::collection($courses),
            'Popular online courses retrieved successfully'
        );
    }


    public function trainerOnlineCourses(Request $request)
    {
        $query = Course::with(['category'])
            ->withCount(['enrollments', 'reviews'])
            ->with(['trainers' => function ($query) {
                $query->withPivot('price', 'start_date', 'end_date'); // Load the price and dates from pivot table
            }])
            ->where('is_online', true);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->search($request->search);
        }

        // Filter by trainer
        if ($request->has('trainer_id')) {
            $query->whereHas('trainers', function ($q) use ($request) {
                $q->where('users.id', $request->trainer_id);
            });
        }

        $courses = $query->paginate($request->get('per_page', 15));

        // Transform courses to include all trainers instead of just first one
        $transformedCourses = OnlineCourseResource::collection($courses);
        
        // Get popular courses
        $popularQuery = $this->popularOnlineCoursesMethod();
        $popularCourses = $popularQuery->take(5)->get();

        return $this->successResponse(
            [
                'courses' => $transformedCourses,
                'popular' => OnlineCourseResource::collection($popularCourses)
            ],
            'Trainer online courses retrieved successfully'
        );
    }

    public function trainerOnlineCoursesShow(Course $course): JsonResponse
    {
        $course->load(['category'])
            ->loadCount(['onlineCourseBookings', 'reviews'])
            ->load(['trainers' => function ($query) {
                $query->withPivot('price', 'start_date', 'end_date');
            }]);

        return $this->successResponse(
            new OnlineCourseResource($course),
            'Course details retrieved successfully'
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
    public function show(Course $course)
    {
        $course->load(['category', 'trainingCenters', 'levels', 'reviews'])
            ->loadCount(['enrollments', 'reviews']);
        return $course;
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
