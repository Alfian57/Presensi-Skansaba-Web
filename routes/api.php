<?php

use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
})->name('api.health');

// API Version 1
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Authentication (public)
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {

        // Auth protected routes
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/me', [AuthController::class, 'me'])->name('me');
        });

        // Profile management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
            Route::post('/photo', [ProfileController::class, 'uploadPhoto'])->name('photo.upload');
            Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('photo.delete');
        });

        // Attendance
        Route::prefix('attendances')->name('attendances.')->group(function () {
            Route::get('/today', [AttendanceController::class, 'today'])->name('today');
            Route::get('/history', [AttendanceController::class, 'history'])->name('history');
            Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
            Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
            Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        });

        // Schedules
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/today', [ScheduleController::class, 'today'])->name('today');
            Route::get('/weekly', [ScheduleController::class, 'weekly'])->name('weekly');
            Route::get('/day/{day}', [ScheduleController::class, 'byDay'])->name('by-day');
        });
    });
});
