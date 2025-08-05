<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\PhoneVerificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\TrainingCenterController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Interests (public for registration)
    Route::get('interests', [InterestController::class, 'index']);
    Route::get('interests/{interest}', [InterestController::class, 'show']);

    // Institutes/Training Centers (public)
    Route::get('institutes', [TrainingCenterController::class, 'index']);
    Route::get('institutes/{trainingCenter}', [TrainingCenterController::class, 'show']);

    // Courses (public)
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{course}', [CourseController::class, 'show']);

    // Categories (public)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);
});

// Protected routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Home Screen
    Route::get('home', [HomeController::class, 'index']);

    // User Profile Management
    Route::prefix('users/me')->group(function () {
        Route::get('/', [UserProfileController::class, 'show']);
        Route::put('/', [UserProfileController::class, 'update']);
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
