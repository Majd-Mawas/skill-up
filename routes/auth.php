<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PhoneVerificationController;

// Registration
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Phone Verification for Registration
Route::get('/verify-phone/register', [PhoneVerificationController::class, 'showRegisterVerification'])
    ->middleware('guest')
    ->name('verification.phone.register');
Route::post('/verify-phone/register/send', [PhoneVerificationController::class, 'sendRegisterVerification'])
    ->middleware('guest')
    ->name('verification.phone.register.send');
Route::post('/verify-phone/register/verify', [PhoneVerificationController::class, 'verifyRegisterCode'])
    ->middleware('guest')
    ->name('verification.phone.register.verify');

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
