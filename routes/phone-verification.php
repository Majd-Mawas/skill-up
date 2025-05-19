<?php

use App\Http\Controllers\Auth\PhonePasswordResetController;
use App\Http\Controllers\Auth\PhoneVerificationController;
use Illuminate\Support\Facades\Route;

// Phone Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/verify-phone', [PhoneVerificationController::class, 'show'])
        ->name('verification.phone');

    Route::post('/verify-phone/send', [PhoneVerificationController::class, 'send'])
        ->name('verification.phone.send');

    Route::post('/verify-phone/verify', [PhoneVerificationController::class, 'verify'])
        ->name('verification.phone.verify');
});

// Phone Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password/phone', [PhonePasswordResetController::class, 'showRequestForm'])
        ->name('password.phone.request');

    Route::post('/forgot-password/phone', [PhonePasswordResetController::class, 'sendResetCode'])
        ->name('password.phone.send');

    Route::get('/reset-password/phone/verify', [PhonePasswordResetController::class, 'showVerifyForm'])
        ->name('password.phone.verify.code');

    Route::post('/reset-password/phone/verify', [PhonePasswordResetController::class, 'verifyCode'])
        ->name('password.phone.verify');

    Route::get('/reset-password/phone', [PhonePasswordResetController::class, 'showResetForm'])
        ->name('password.phone.reset');

    Route::post('/reset-password/phone', [PhonePasswordResetController::class, 'reset'])
        ->name('password.phone.reset.submit');
});
