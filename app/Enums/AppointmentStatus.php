<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case AGENDADO = 'agendado';
    case CANCELADO = 'cancelado';
    case CONCLUIDO = 'concluido';

    public function label(): string
    {
        return match ($this) {
            self::AGENDADO => 'Agendado',
            self::CANCELADO => 'Cancelado',
            self::CONCLUIDO => 'Concluído',
        };
    }
}
