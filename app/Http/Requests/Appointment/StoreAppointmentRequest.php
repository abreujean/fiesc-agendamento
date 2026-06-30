<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'attendant_id' => ['required', 'exists:users,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'attendant_id.required' => 'O campo atendente é obrigatório.',
            'attendant_id.exists' => 'Atendente não encontrado.',
            'client_name.required' => 'O campo nome do cliente é obrigatório.',
            'client_email.email' => 'Formato de e-mail inválido.',
            'date.required' => 'O campo data é obrigatório.',
            'date.after_or_equal' => 'A data deve ser hoje ou futura.',
            'start_time.required' => 'O campo hora inicial é obrigatório.',
            'start_time.date_format' => 'Formato de hora inválido (HH:mm).',
            'end_time.required' => 'O campo hora final é obrigatório.',
            'end_time.date_format' => 'Formato de hora inválido (HH:mm).',
            'end_time.after' => 'A hora final deve ser maior que a hora inicial.',
        ];
    }
}
