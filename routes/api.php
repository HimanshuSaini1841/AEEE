<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\MockTestController;
use App\Http\Controllers\Api\ProfileOptionsController;
use App\Http\Controllers\Api\PyqController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AEEE Mobile App REST API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // 1. Dynamic Options (Classes, States, Preparing For) - High-speed cached
    Route::get('/options/profile-fields', [ProfileOptionsController::class, 'index'])
        ->name('api.options.profile-fields');

    // 2. PYQ Question Papers Catalog & In-App Secure Stream
    Route::get('/pyqs', [PyqController::class, 'index'])->name('api.pyqs.index');
    Route::get('/pyqs/{id}/download', [PyqController::class, 'download'])->name('api.pyqs.download');

    // 3. Screen 1: Continue with Google Sign-in & Credential Login for Reviewers
    Route::post('/auth/google', [AuthController::class, 'googleLogin'])
        ->middleware('throttle:60,1')
        ->name('api.auth.google');

    Route::post('/auth/credentials-login', [AuthController::class, 'credentialsLogin'])
        ->middleware('throttle:30,1')
        ->name('api.auth.credentials-login');

    // App Remote Config (e.g. show_demo_login flag)
    Route::get('/app-config', [AuthController::class, 'appConfig'])
        ->name('api.app-config');

    // 4. Protected Routes (Requires Sanctum Token)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Mock Tests Catalog & History
        Route::get('/mock-tests', [MockTestController::class, 'index'])->name('api.mock-tests.index');
        Route::get('/mock-tests/attempts', [MockTestController::class, 'studentAttempts'])->name('api.mock-tests.attempts');
        Route::get('/mock-tests/attempts/{attemptId}', [MockTestController::class, 'attemptDetail'])->name('api.mock-tests.attempt-detail');

        // Mock Test Execution & Details
        Route::get('/mock-tests/{id}', [MockTestController::class, 'show'])->where('id', '[0-9]+')->name('api.mock-tests.show');
        Route::get('/mock-tests/{id}/take', [MockTestController::class, 'take'])->where('id', '[0-9]+')->name('api.mock-tests.take');
        Route::post('/mock-tests/{id}/submit', [MockTestController::class, 'submit'])->where('id', '[0-9]+')->name('api.mock-tests.submit');

        // Screen 2: Complete Profile + Trigger Fast2SMS OTP
        Route::post('/auth/complete-profile', [AuthController::class, 'completeProfile'])
            ->middleware('throttle:20,1')
            ->name('api.auth.complete-profile');

        // Screen 3: Verify OTP
        Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])
            ->middleware('throttle:15,1')
            ->name('api.auth.verify-otp');

        // Resend OTP via Fast2SMS
        Route::post('/auth/resend-otp', [AuthController::class, 'resendOtp'])
            ->middleware('throttle:5,1')
            ->name('api.auth.resend-otp');

        // Authenticated Student Profile & Logout
        Route::get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::post('/student/profile', [AuthController::class, 'updateProfile'])->name('api.student.update-profile');
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');

        // Register Firebase FCM Device Token
        Route::post('/device/fcm-token', [FcmTokenController::class, 'store'])
            ->name('api.device.fcm-token');
    });
});
