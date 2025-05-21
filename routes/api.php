<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TrainingCenterController;
use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Areas
    Route::apiResource('areas', AreaController::class);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Training Centers
    Route::apiResource('training-centers', TrainingCenterController::class);

    // Halls
    Route::apiResource('halls', HallController::class);

    // Courses
    Route::apiResource('courses', CourseController::class);

    // Sessions
    Route::apiResource('sessions', SessionController::class);
});

// Protected routes
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Enrollments
    Route::apiResource('enrollments', EnrollmentController::class);

    // Payments
    Route::apiResource('payments', PaymentController::class);

    // Invoices
    Route::apiResource('invoices', InvoiceController::class);

    // Certificates
    Route::apiResource('certificates', CertificateController::class);

    // Halls
    Route::apiResource('halls', HallController::class);
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
    Route::post('/reset-password', [NewPasswordController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
