<?php

use App\Http\Controllers\Training\AreaController;
use App\Http\Controllers\Training\CategoryController;
use App\Http\Controllers\Training\CourseController;
use App\Http\Controllers\Training\EnrollmentController;
use App\Http\Controllers\Training\HallController;
use App\Http\Controllers\Training\InterestController;
use App\Http\Controllers\Training\OnlineCourseController;
use App\Http\Controllers\Training\ReviewController;
use App\Http\Controllers\Training\SessionController;
use App\Http\Controllers\Training\TrainingCenterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->name('web.')->group(function () {
    Route::resource('areas', AreaController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('online-courses', OnlineCourseController::class);
    Route::resource('courses.sessions', SessionController::class);
    Route::resource('courses.enrollments', EnrollmentController::class);
    Route::resource('courses.reviews', ReviewController::class);
    Route::resource('training-centers', TrainingCenterController::class);
    Route::get('training-centers/{trainingCenter}/export', [TrainingCenterController::class, 'export'])->name('training-centers.export');
    Route::resource('training-centers.halls', HallController::class);
    Route::resource('interests', InterestController::class);
});
