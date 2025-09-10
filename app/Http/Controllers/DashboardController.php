<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseBooking;
use App\Models\HallBooking;
use App\Models\ICDLCardBooking;
use App\Models\ICDLTestBooking;
use App\Models\OnlineCourseBooking;
use App\Models\TrainingCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get statistics for the dashboard
     */
    public function getStatistics()
    {
        // Count of training centers
        $trainingCentersCount = TrainingCenter::where('status', 'active')->count();
        
        // Count of trainers
        $trainersCount = User::whereHas('roles', function($query) {
            $query->where('name', 'trainer');
        })->count();
        
        // Count of courses
        $coursesCount = Course::count();
        
        // Count of students/users
        $studentsCount = User::whereHas('roles', function($query) {
            $query->where('name', 'student');
        })->count();
        
        // Most booked training centers
        $mostBookedTrainingCenters = TrainingCenter::withCount([
            'courseBookings', 
            'icdlTestBookings', 
            'icdlCardBookings'
        ])
        ->having(DB::raw('course_bookings_count + icdl_test_bookings_count + icdl_card_bookings_count'), '>', 0)
        ->orderByRaw('course_bookings_count + icdl_test_bookings_count + icdl_card_bookings_count DESC')
        ->take(5)
        ->get()
        ->map(function($center) {
            return [
                'name' => $center->name,
                'bookings_count' => $center->course_bookings_count + $center->icdl_test_bookings_count + $center->icdl_card_bookings_count
            ];
        });
        
        // Most booked courses
        $mostBookedCourses = Course::withCount(['courseBookings', 'onlineCourseBookings'])
        ->having(DB::raw('course_bookings_count + online_course_bookings_count'), '>', 0)
        ->orderByRaw('course_bookings_count + online_course_bookings_count DESC')
        ->take(5)
        ->get()
        ->map(function($course) {
            return [
                'name' => $course->name,
                'bookings_count' => $course->course_bookings_count + $course->online_course_bookings_count
            ];
        });
        
        // Total bookings by type
        $bookingsByType = [
            'course' => CourseBooking::count(),
            'online_course' => OnlineCourseBooking::count(),
            'hall' => HallBooking::count(),
            'icdl_test' => ICDLTestBooking::count(),
            'icdl_card' => ICDLCardBooking::count(),
        ];
        
        return [
            'training_centers_count' => $trainingCentersCount,
            'trainers_count' => $trainersCount,
            'courses_count' => $coursesCount,
            'students_count' => $studentsCount,
            'most_booked_training_centers' => $mostBookedTrainingCenters,
            'most_booked_courses' => $mostBookedCourses,
            'bookings_by_type' => $bookingsByType
        ];
    }
}