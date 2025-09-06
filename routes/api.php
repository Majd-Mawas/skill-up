<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\PhoneVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HallBookingController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\TrainingCenterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Interests (public for registration)
    Route::get('interests', [InterestController::class, 'index']);
    Route::get('interests/{interest}', [InterestController::class, 'show']);

    // Institutes/Training Centers (public)
    Route::get('institutes', [TrainingCenterController::class, 'index']);
    // Route::get('institutes/online-courses', [TrainingCenterController::class, 'onlineCourses']);
    Route::get('institutes/{trainingCenter}', [TrainingCenterController::class, 'show']);
    Route::get('institutes/{trainingCenter}/courses', [TrainingCenterController::class, 'courses']);
    Route::get('institutes/{trainingCenter}/halls', [TrainingCenterController::class, 'halls']);

    // Courses (public)
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/online', [CourseController::class, 'trainerOnlineCourses']);
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

    // Admin routes for managing institutes, courses, and categories
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('institutes', TrainingCenterController::class)->except(['index', 'show']);
        Route::apiResource('courses', CourseController::class)->except(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    });

    // Online course trainer routes (for admin and trainers)
    Route::middleware('role:admin,trainer')->group(function () {
        Route::get('courses/{course}/trainers', [\App\Http\Controllers\Api\CourseTrainerController::class, 'index']);
        Route::post('courses/{course}/trainers', [\App\Http\Controllers\Api\CourseTrainerController::class, 'store']);
        Route::get('courses/{course}/trainers/{user}', [\App\Http\Controllers\Api\CourseTrainerController::class, 'show']);
        Route::put('courses/{course}/trainers/{user}', [\App\Http\Controllers\Api\CourseTrainerController::class, 'update']);
        Route::delete('courses/{course}/trainers/{user}', [\App\Http\Controllers\Api\CourseTrainerController::class, 'destroy']);
    });

    // Hall booking routes
    // Route::apiResource('hall/bookings', BookingController::class);
    Route::post('hall/bookings', [BookingController::class, 'createHallBooking']);
    // Route::apiResource('hall-bookings', HallBookingController::class);

    // Course booking routes
    Route::prefix('course/bookings')->group(function () {
        Route::get('/available-courses', [\App\Http\Controllers\API\CourseBookingController::class, 'getAvailableCourses']);
        Route::get('/', [\App\Http\Controllers\API\CourseBookingController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\API\CourseBookingController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\API\CourseBookingController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\API\CourseBookingController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\API\CourseBookingController::class, 'destroy']);
    });

    // Online Course booking routes
    Route::prefix('course/online/bookings')->group(function () {
        Route::get('/available-courses', [\App\Http\Controllers\Api\OnlineCourseBookingController::class, 'getAvailableOnlineCourses']);
        Route::get('/', [\App\Http\Controllers\Api\OnlineCourseBookingController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\OnlineCourseBookingController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\OnlineCourseBookingController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\OnlineCourseBookingController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\OnlineCourseBookingController::class, 'destroy']);
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
