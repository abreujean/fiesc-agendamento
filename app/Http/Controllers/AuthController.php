<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request);
    }

    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    public function me(): JsonResponse
    {
        return $this->authService->me();
    }
}
