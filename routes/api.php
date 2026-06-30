<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    Route::get('/availabilities', [AvailabilityController::class, 'index']);
    Route::get('/availabilities/{availability}', [AvailabilityController::class, 'show']);

    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/available-slots', [AppointmentController::class, 'availableSlots']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);

    Route::middleware('admin')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        Route::post('/availabilities', [AvailabilityController::class, 'store']);
        Route::put('/availabilities/{availability}', [AvailabilityController::class, 'update']);
        Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy']);
    });
});
