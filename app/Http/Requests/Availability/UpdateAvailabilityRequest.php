<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'day_of_week' => ['sometimes', 'integer', 'between:0,6'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'day_of_week.between' => 'Dia da semana inválido (0 a 6).',
            'start_time.date_format' => 'Formato de hora inválido (HH:mm).',
            'end_time.date_format' => 'Formato de hora inválido (HH:mm).',
            'end_time.after' => 'A hora final deve ser maior que a hora inicial.',
        ];
    }
}
