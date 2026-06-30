<?php

namespace App\Services;

use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Http\Requests\Availability\UpdateAvailabilityRequest;
use App\Models\Availability;
use Illuminate\Http\JsonResponse;

class AvailabilityService
{
    public function index(): JsonResponse
    {
        $availabilities = Availability::with('user')->get();

        return response()->json($availabilities, 200);
    }

    public function store(StoreAvailabilityRequest $request): JsonResponse
    {
        $availability = Availability::create([
            'user_id' => $request->user_id,
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
        $availability->update($request->validated());

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
