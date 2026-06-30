<?php

namespace App\Services;

use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class AppointmentService
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with('attendant')->get();

        return response()->json($appointments, 200);
    }

    public function availableSlots(int $attendantId, string $date): JsonResponse
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeek;

        $availabilities = Availability::active()
            ->byDayOfWeek($dayOfWeek)
            ->where('user_id', $attendantId)
            ->get();

        $busySlots = Appointment::scheduled()
            ->byDate($date)
            ->byAttendant($attendantId)
            ->get()
            ->mapWithKeys(fn ($a) => [$a->start_time->format('H:i') => true])
            ->toArray();

        $slots = [];
        $interval = 30;

        foreach ($availabilities as $availability) {
            $start = Carbon::parse($availability->start_time);
            $end = Carbon::parse($availability->end_time);

            while ($start->copy()->addMinutes($interval) <= $end) {
                $slot = $start->format('H:i');
                $isBusy = isset($busySlots[$slot]);

                $slots[] = [
                    'start_time' => $slot,
                    'end_time' => $start->copy()->addMinutes($interval)->format('H:i'),
                    'busy' => $isBusy,
                ];

                $start->addMinutes($interval);
            }
        }

        $appointments = Appointment::whereDate('date', $date)
            ->where('attendant_id', $attendantId)
            ->with('attendant')
            ->get()
            ->map(fn ($a) => [
                'public_id' => $a->public_id,
                'client_name' => $a->client_name,
                'client_email' => $a->client_email,
                'start_time' => $a->start_time->format('H:i'),
                'end_time' => $a->end_time->format('H:i'),
                'status' => $a->status->value,
                'attendant' => $a->attendant?->name ?? '-',
            ])->values();

        return response()->json([
            'attendant_id' => $attendantId,
            'date' => $date,
            'slots' => $slots,
            'appointments' => $appointments,
        ], 200);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $attendantId = User::where('public_id', $request->attendant_id)->value('id');

        $this->validateAvailability(
            $attendantId,
            $request->date,
            $request->start_time,
            $request->end_time
        );

        $appointment = Appointment::create([
            'attendant_id' => $attendantId,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'message' => 'Agendamento criado com sucesso.',
            'appointment' => $appointment,
        ], 201);
    }

    public function cancel(Appointment $appointment): JsonResponse
    {
        $appointment->cancel();

        return response()->json([
            'message' => 'Agendamento cancelado com sucesso.',
            'appointment' => $appointment,
        ], 200);
    }

    public function myAppointments(int $userId): JsonResponse
    {
        $appointments = Appointment::with('attendant')
            ->where('attendant_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(fn ($a) => [
                'public_id' => $a->public_id,
                'client_name' => $a->client_name,
                'client_email' => $a->client_email,
                'date' => $a->date->format('Y-m-d'),
                'start_time' => $a->start_time->format('H:i'),
                'end_time' => $a->end_time->format('H:i'),
                'status' => $a->status->value,
            ])->values();

        return response()->json($appointments, 200);
    }

    private function validateAvailability(int $attendantId, string $date, string $startTime, string $endTime): void
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeek;

        $hasAvailability = Availability::active()
            ->byDayOfWeek($dayOfWeek)
            ->where('user_id', $attendantId)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->exists();

        if (!$hasAvailability) {
            abort(400, 'Não há disponibilidade para o horário selecionado.');
        }

        $hasConflict = Appointment::scheduled()
            ->byDate($date)
            ->byAttendant($attendantId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($hasConflict) {
            abort(400, 'Já existe um agendamento neste horário.');
        }
    }
}
