<?php

use App\Http\Controllers\AuthController;
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
    Route::get('/users/create', fn () => view('users.create'))->name('users.create')->middleware('admin');
    Route::get('/users/{user}/edit', fn (User $user) => view('users.edit', compact('user')))->name('users.edit');

    Route::get('/availabilities', fn () => view('availabilities.index'))->name('availabilities.index')->middleware('admin');
    Route::get('/availabilities/create', fn () => view('availabilities.create'))->name('availabilities.create')->middleware('admin');
    Route::get('/availabilities/{availability}/edit', fn (Availability $availability) => view('availabilities.create', compact('availability')))->name('availabilities.edit')->middleware('admin');

    Route::get('/appointments', fn () => view('appointments.index'))->name('appointments.index');
    Route::get('/meus-agendamentos', fn () => view('profile'))->name('profile');
});

Route::fallback(fn () => redirect('/'));
