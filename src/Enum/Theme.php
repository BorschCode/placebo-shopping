<?php

namespace App\Enum;

enum Theme: string
{
    case Olx = 'olx';
    case Autoria = 'autoria';
    case Experinza = 'experinza';

    public function label(): string
    {
        return match($this) {
            self::Olx => 'OLX',
            self::Autoria => 'AutoRia',
            self::Experinza => 'Experinza',
        };
    }
}
