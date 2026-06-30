<?php

namespace App\Enums;

enum UserProfile: string
{
    case ADMINISTRADOR = 'administrador';
    case ATENDENTE = 'atendente';

    public function label(): string
    {
        return match ($this) {
            self::ADMINISTRADOR => 'Administrador',
            self::ATENDENTE => 'Atendente',
        };
    }
}
