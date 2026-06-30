<?php

namespace App\Services;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user, 200);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile' => $request->profile,
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso.',
            'user' => $user,
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update([
            'name' => $request->name,
            'profile' => $request->profile,
        ]);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'user' => $user,
        ], 200);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'Usuário excluído com sucesso.',
        ], 200);
    }
}
