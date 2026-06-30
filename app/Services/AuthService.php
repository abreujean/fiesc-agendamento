<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ], 200);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'user' => Auth::user(),
        ], 200);
    }
}
