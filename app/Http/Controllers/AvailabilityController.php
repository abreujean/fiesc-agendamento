<?php

namespace App\Http\Controllers;

use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Http\Requests\Availability\UpdateAvailabilityRequest;
use App\Models\Availability;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function __construct(
        private AvailabilityService $availabilityService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->availabilityService->index();
    }

    public function show(Availability $availability): JsonResponse
    {
        return $this->availabilityService->show($availability);
    }

    public function store(StoreAvailabilityRequest $request): JsonResponse
    {
        return $this->availabilityService->store($request);
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability): JsonResponse
    {
        return $this->availabilityService->update($request, $availability);
    }

    public function destroy(Availability $availability): JsonResponse
    {
        return $this->availabilityService->destroy($availability);
    }
}
