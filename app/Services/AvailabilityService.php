<?php

namespace App\Services;

use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Http\Requests\Availability\UpdateAvailabilityRequest;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AvailabilityService
{
    public function index(): JsonResponse
    {
        $availabilities = Availability::with('user')->get();

        return response()->json($availabilities, 200);
    }

    public function show(Availability $availability): JsonResponse
    {
        return response()->json($availability->load('user'), 200);
    }

    public function store(StoreAvailabilityRequest $request): JsonResponse
    {
        $userId = User::where('public_id', $request->user_id)->value('id');

        $conflict = Availability::where('user_id', $userId)
            ->where('day_of_week', $request->day_of_week)
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time)
            ->exists();

        if ($conflict) {
            abort(400, 'Já existe uma disponibilidade para esse atendente no mesmo dia e horário.');
        }

        $availability = Availability::create([
            'user_id' => $userId,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'message' => 'Disponibilidade criada com sucesso.',
            'availability' => $availability,
        ], 201);
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability): JsonResponse
    {
        $validated = $request->validated();

        $dayOfWeek = $validated['day_of_week'] ?? $availability->day_of_week;
        $startTime = $validated['start_time'] ?? $availability->start_time;
        $endTime = $validated['end_time'] ?? $availability->end_time;

        $conflict = Availability::where('user_id', $availability->user_id)
            ->where('id', '!=', $availability->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflict) {
            abort(400, 'Já existe uma disponibilidade para esse atendente no mesmo dia e horário.');
        }

        $availability->update($validated);

        return response()->json([
            'message' => 'Disponibilidade atualizada com sucesso.',
            'availability' => $availability,
        ], 200);
    }

    public function destroy(Availability $availability): JsonResponse
    {
        $availability->delete();

        return response()->json([
            'message' => 'Disponibilidade excluída com sucesso.',
        ], 200);
    }
}
