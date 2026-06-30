<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'same:password'],
            'profile' => ['required', 'string', 'in:administrador,atendente'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Formato de e-mail inválido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password_confirmation.required' => 'O campo confirme a senha é obrigatório.',
            'password_confirmation.same' => 'A confirmação de senha não corresponde.',
            'profile.required' => 'O campo tipo de usuário é obrigatório.',
            'profile.in' => 'Tipo de usuário inválido.',
        ];
    }
}
