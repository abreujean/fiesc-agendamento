<?php

namespace App\Services;

use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Availability;
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
            ->pluck('start_time', 'start_time')
            ->toArray();

        $slots = [];
        $interval = 30;

        foreach ($availabilities as $availability) {
            $start = Carbon::parse($availability->start_time);
            $end = Carbon::parse($availability->end_time);

            while ($start->copy()->addMinutes($interval) <= $end) {
                $slot = $start->format('H:i');

                if (!isset($busySlots[$slot])) {
                    $slots[] = [
                        'start_time' => $slot,
                        'end_time' => $start->copy()->addMinutes($interval)->format('H:i'),
                    ];
                }

                $start->addMinutes($interval);
            }
        }

        return response()->json([
            'attendant_id' => $attendantId,
            'date' => $date,
            'available_slots' => $slots,
        ], 200);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $this->validateAvailability(
            $request->attendant_id,
            $request->date,
            $request->start_time,
            $request->end_time
        );

        $appointment = Appointment::create([
            'attendant_id' => $request->attendant_id,
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
