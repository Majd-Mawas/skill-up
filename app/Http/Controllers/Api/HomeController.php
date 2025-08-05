<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\TrainingCenterResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Enrollment;
use App\Models\TrainingCenter;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->user()->load(['area', 'interests', 'enrollments.course.levels']);

        // Get recommended courses based on user's interests or completed courses
        $recommendedCourses = $this->getRecommendedCourses($user);

        // Get popular courses (most enrolled)
        $popularCourses = $this->getPopularCourses();

        // Get active categories
        $categories = Category::withCount('courses')
            ->ordered()
            ->take(10)
            ->get();

        // Get active institutes
        $institutes = TrainingCenter::where('status', 'active')
            ->withCount(['courses', 'reviews'])
            ->take(5)
            ->get();

        return $this->successResponse([
            'user' => new UserResource($user),
            'recommended_courses' => CourseResource::collection($recommendedCourses),
            'popular_courses' => CourseResource::collection($popularCourses),
            'categories' => CategoryResource::collection($categories),
            'institutes' => TrainingCenterResource::collection($institutes),
        ], 'Home data retrieved successfully');
    }

    /**
     * Get recommended courses based on user's interests or completed courses.
     */
    private function getRecommendedCourses($user)
    {
        // Check if user has completed any courses
        $completedEnrollments = $user->enrollments()->where('status', 'completed')->get();

        if ($completedEnrollments->isNotEmpty()) {
            // Recommend next level courses for completed courses
            $recommendedCourseIds = [];

            foreach ($completedEnrollments as $enrollment) {
                $course = $enrollment->course;

                // If the course has levels, recommend the next level course
                if ($course->levels()->count() > 0) {
                    $lastLevel = $course->lastLevel();
                    $completedLevel = $enrollment->completed_level_id ?? null;

                    // If user hasn't completed the last level, recommend the next level
                    if ($completedLevel && $completedLevel != $lastLevel->id) {
                        $currentLevel = CourseLevel::find($completedLevel);
                        $nextLevel = $currentLevel ? $currentLevel->nextLevel() : null;

                        if ($nextLevel) {
                            $recommendedCourseIds[] = $course->id;
                        }
                    }
                }

                // Also recommend courses in the same category
                if ($course->category_id) {
                    $categoryCourses = Course::where('category_id', $course->category_id)
                        ->where('id', '!=', $course->id)
                        ->take(2)
                        ->pluck('id')
                        ->toArray();

                    $recommendedCourseIds = array_merge($recommendedCourseIds, $categoryCourses);
                }
            }

            if (!empty($recommendedCourseIds)) {
                return Course::whereIn('id', array_unique($recommendedCourseIds))
                    ->with(['category', 'trainingCenters'])
                    ->withCount(['enrollments', 'reviews'])
                    ->take(5)
                    ->get();
            }
        }

        // If no completed courses or no recommendations from them,
        // recommend based on interests
        $interestIds = $user->interests->pluck('id')->toArray();

        if (!empty($interestIds)) {
            // Since there's no direct relationship between interests and courses,
            // we'll use categories as a proxy for interests
            $categoryCourses = Course::whereHas('category', function ($query) use ($interestIds) {
                // Match categories with similar names to interests
                $query->whereIn('id', function ($subquery) use ($interestIds) {
                    $subquery->select('id')
                        ->from('categories')
                        ->whereIn(DB::raw('LOWER(name)'), function ($interestQuery) use ($interestIds) {
                            $interestQuery->select(DB::raw('LOWER(name)'))
                                ->from('interests')
                                ->whereIn('id', $interestIds);
                        });
                });
            })
                ->with(['category', 'trainingCenters'])
                ->withCount(['enrollments', 'reviews'])
                ->take(5)
                ->get();

            if ($categoryCourses->isNotEmpty()) {
                return $categoryCourses;
            }
        }

        // Fallback: return some active courses if no recommendations can be made
        return Course::with(['category', 'trainingCenters'])
            ->withCount(['enrollments', 'reviews'])
            ->inRandomOrder()
            ->take(5)
            ->get();
    }

    /**
     * Get popular courses based on enrollment count.
     */
    private function getPopularCourses()
    {
        return Course::withCount(['enrollments', 'reviews'])
            ->orderByDesc('enrollments_count')
            ->with(['category', 'trainingCenters'])
            ->take(5)
            ->get();
    }
}
