<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\PhoneVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\TrainingCenterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseBookingController;
use App\Http\Controllers\Api\CourseTrainerController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OnlineCourseBookingController;
use App\Http\Controllers\Api\ICDLCardBookingController;
use App\Http\Controllers\Api\ICDLTestBookingController;
use App\Http\Controllers\Api\PlacementTestBookingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Interests (public for registration)
    Route::get('interests', [InterestController::class, 'index']);
    Route::get('interests/{interest}', [InterestController::class, 'show']);

    Route::get('institutes', [TrainingCenterController::class, 'index']);

    Route::get('institutes/{trainingCenter}', [TrainingCenterController::class, 'show']);
    Route::get('institutes/{trainingCenter}/courses', [TrainingCenterController::class, 'courses']);
    Route::get('institutes/{trainingCenter}/courses/{course}', [TrainingCenterController::class, 'coursesShow']);
    Route::get('institutes/{trainingCenter}/halls', [TrainingCenterController::class, 'halls']);
    // Route::get('institutes/{trainingCenter}/icdl-cards/available-times', [ICDLCardBookingController::class, 'getAvailableTimes']);
    Route::get('institutes/{trainingCenter}/icdl-tests/available-times', [ICDLTestBookingController::class, 'getAvailableTimes']);

    // Courses (public)
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/online', [CourseController::class, 'trainerOnlineCourses']);
    Route::get('courses/online/popular', [CourseController::class, 'popularOnlineCourses']);
    Route::get('courses/online/{course}', [CourseController::class, 'trainerOnlineCoursesShow']);
    Route::get('courses/{course}', [CourseController::class, 'show']);

    // Halls (public)
    Route::get('halls', [HallController::class, 'index']);
    Route::get('halls/{hall}', [HallController::class, 'show']);

    // Categories (public)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);
});

// Protected routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Home Screen
    Route::get('home', [HomeController::class, 'index']);

    // User Profile Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserProfileController::class, 'show']);
        Route::post('/', [UserProfileController::class, 'update']);
        Route::post('avatar', [UserProfileController::class, 'uploadAvatar']);
        Route::delete('avatar', [UserProfileController::class, 'deleteAvatar']);
        Route::put('password', [UserProfileController::class, 'changePassword']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('institutes', TrainingCenterController::class)->except(['index', 'show']);
        Route::apiResource('courses', CourseController::class)->except(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    });

    Route::middleware('role:admin,trainer')->group(function () {
        Route::get('courses/{course}/trainers', [CourseTrainerController::class, 'index']);
        Route::post('courses/{course}/trainers', [CourseTrainerController::class, 'store']);
        Route::get('courses/{course}/trainers/{user}', [CourseTrainerController::class, 'show']);
        Route::put('courses/{course}/trainers/{user}', [CourseTrainerController::class, 'update']);
        Route::delete('courses/{course}/trainers/{user}', [CourseTrainerController::class, 'destroy']);
    });

    Route::post('hall/bookings', [BookingController::class, 'createHallBooking']);

    // Course booking routes
    Route::prefix('course/bookings')->group(function () {
        // Route::get('/available-courses', [CourseBookingController::class, 'getAvailableCourses']);
        // Route::get('/', [CourseBookingController::class, 'index']);
        Route::post('/', [CourseBookingController::class, 'store']);
        // Route::get('/{id}', [CourseBookingController::class, 'show']);
        // Route::put('/{id}', [CourseBookingController::class, 'update']);
        // Route::delete('/{id}', [CourseBookingController::class, 'destroy']);
        // Route::get('/current', [CourseBookingController::class, 'currentCourses']);
        // Route::get('/finished', [CourseBookingController::class, 'finishedCourses']);
        Route::get('/all', [CourseBookingController::class, 'currentAndFinishedCourses']);
    });

    // Hall booking routes
    Route::prefix('hall/bookings')->group(function () {
        Route::get('/all', [\App\Http\Controllers\Api\HallBookingController::class, 'index']);
    });

    // Online Course booking routes
    Route::prefix('course/online/bookings')->group(function () {
        // Route::get('/available-courses', [OnlineCourseBookingController::class, 'getAvailableOnlineCourses']);
        // Route::get('/', [OnlineCourseBookingController::class, 'index']);
        Route::post('/', [OnlineCourseBookingController::class, 'store']);
        // Route::get('/{id}', [OnlineCourseBookingController::class, 'show']);
        // Route::put('/{id}', [OnlineCourseBookingController::class, 'update']);
        // Route::delete('/{id}', [OnlineCourseBookingController::class, 'destroy']);
        // Route::get('/current', [OnlineCourseBookingController::class, 'currentCourses']);
        // Route::get('/finished', [OnlineCourseBookingController::class, 'finishedCourses']);
        Route::get('/all', [OnlineCourseBookingController::class, 'currentAndFinishedCourses']);
    });

    Route::prefix('icdl-card/bookings')->group(function () {
        Route::get('/', [ICDLCardBookingController::class, 'index']);
        Route::post('/', [ICDLCardBookingController::class, 'store']);
    });

    Route::prefix('icdl-test/bookings')->group(function () {
        Route::get('/', [ICDLTestBookingController::class, 'index']);
        Route::post('/', [ICDLTestBookingController::class, 'store']);
        Route::get('/all', [ICDLTestBookingController::class, 'index']);
    });

    Route::prefix('placement-test/bookings')->group(function () {
        Route::post('/', [BookingController::class, 'createPlacementTestBooking']);
        Route::get('/all', [PlacementTestBookingController::class, 'index']);
    });
});  // End of protected routes

// Public routes
Route::prefix('v1')->group(function () {
    Route::prefix('icdl-card-bookings')->group(function () {
        Route::get('/available-times', [ICDLCardBookingController::class, 'getAvailableTimes']);
    });

    Route::prefix('icdl-test-bookings')->group(function () {
        Route::get('/available-times', [ICDLTestBookingController::class, 'getAvailableTimes']);
    });
});

// Authentication routes (Guest)
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::post('/request-reset-code', [PasswordResetController::class, 'requestResetCode']);
    Route::post('/verify-reset-code', [PasswordResetController::class, 'verifyResetCode']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

    Route::post('/send-verification-code', [PhoneVerificationController::class, 'sendVerificationCode']);
    Route::post('/verify-code', [PhoneVerificationController::class, 'verifyCode']);
});

Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
