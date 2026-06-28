<?php

namespace KaayDem\Models\Enums;

class TripStatus
{
    const ACTIVE = 'active';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    
    public static function label($status): string
    {
        return match($status) {
            self::ACTIVE => 'Actif',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
            default => 'inconnu'
        };
    }
}