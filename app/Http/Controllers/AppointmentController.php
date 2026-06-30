<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->appointmentService->index();
    }

    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'attendant_id' => ['required', 'exists:users,public_id'],
            'date' => ['required', 'date'],
        ]);

        $attendantId = User::where('public_id', $request->attendant_id)->value('id');

        return $this->appointmentService->availableSlots(
            (int) $attendantId,
            $request->date
        );
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        return $this->appointmentService->store($request);
    }

    public function cancel(Appointment $appointment): JsonResponse
    {
        return $this->appointmentService->cancel($appointment);
    }
}
