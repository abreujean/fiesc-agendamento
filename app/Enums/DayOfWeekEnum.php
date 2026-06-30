<?php

namespace App\Enums;

enum DayOfWeekEnum: int
{
    case DOMINGO = 0;
    case SEGUNDA = 1;
    case TERCA = 2;
    case QUARTA = 3;
    case QUINTA = 4;
    case SEXTA = 5;
    case SABADO = 6;

    public function label(): string
    {
        return match ($this) {
            self::DOMINGO => 'Domingo',
            self::SEGUNDA => 'Segunda-feira',
            self::TERCA => 'Terça-feira',
            self::QUARTA => 'Quarta-feira',
            self::QUINTA => 'Quinta-feira',
            self::SEXTA => 'Sexta-feira',
            self::SABADO => 'Sábado',
        };
    }
}
