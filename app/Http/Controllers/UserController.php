<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->userService->index();
    }

    public function show(User $user): JsonResponse
    {
        return $this->userService->show($user);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        return $this->userService->store($request);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        return $this->userService->update($request, $user);
    }

    public function destroy(User $user): JsonResponse
    {
        return $this->userService->destroy($user);
    }
}
