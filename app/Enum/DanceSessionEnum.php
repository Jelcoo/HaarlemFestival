<?php

namespace App\Enum;

enum DanceSessionEnum: string
{
    case CLUB = 'club';
    case B2B = 'b2b';
    case TIESTO_WORLD = 'tiesto_world';

    public function toString(): string
    {
        return match ($this) {
            self::CLUB => 'Club',
            self::B2B => 'Back2Back',
            self::TIESTO_WORLD => 'TiëstoWorld**',
        };
    }
}
