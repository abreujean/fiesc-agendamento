<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Availability;

Route::get('/login', fn () => view('auth.login'))->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/', fn () => view('dashboard'))->name('dashboard');

    Route::get('/api/user-session', [AuthController::class, 'userSession']);

    Route::get('/users', fn () => view('users.index'))->name('users.index');
    Route::get('/users/create', fn () => view('users.create'))->name('users.create');
    Route::get('/users/{user}/edit', fn (User $user) => view('users.edit', compact('user')))->name('users.edit');

    Route::get('/availabilities', fn () => view('availabilities.index'))->name('availabilities.index');
    Route::get('/availabilities/create', fn () => view('availabilities.create'))->name('availabilities.create');
    Route::get('/availabilities/{availability}/edit', fn (Availability $availability) => view('availabilities.create', compact('availability')))->name('availabilities.edit');

    Route::get('/appointments', fn () => view('appointments.index'))->name('appointments.index');

    Route::fallback(fn () => redirect('/'));
});

Route::fallback(fn () => redirect('/'));
