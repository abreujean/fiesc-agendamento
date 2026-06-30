<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,public_id'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'O campo atendente é obrigatório.',
            'user_id.exists' => 'Atendente não encontrado.',
            'day_of_week.required' => 'O campo dia da semana é obrigatório.',
            'day_of_week.between' => 'Dia da semana inválido (0 a 6).',
            'start_time.required' => 'O campo hora inicial é obrigatório.',
            'start_time.date_format' => 'Formato de hora inválido (HH:mm).',
            'end_time.required' => 'O campo hora final é obrigatório.',
            'end_time.date_format' => 'Formato de hora inválido (HH:mm).',
            'end_time.after' => 'A hora final deve ser maior que a hora inicial.',
            'is_active.required' => 'O campo ativo é obrigatório.',
        ];
    }
}
