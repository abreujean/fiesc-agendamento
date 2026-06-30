<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $this->user()->isAdmin() || $this->user()->id === $user->id;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'profile' => ['required', 'string', 'in:administrador,atendente'],
        ];

        if (!$this->user()->isAdmin()) {
            $rules['profile'][] = Rule::in(['atendente']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'profile.required' => 'O campo tipo de usuário é obrigatório.',
            'profile.in' => 'Tipo de usuário inválido.',
        ];
    }

    public function forbiddenResponse()
    {
        return response()->json([
            'message' => 'Você não tem permissão para realizar esta ação.',
        ], 403);
    }
}
