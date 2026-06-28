<?php

namespace KaayDem\Models\Enums;

class ReservationStatus
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    
    public static function label($status): string
    {
        return match($status) {
            self::PENDING => 'En attente',
            self::CONFIRMED => 'Confirmée',
            self::COMPLETED => 'Terminée',
            self::CANCELLED => 'Annulée',
            default=>'inconnu'
        };
    }
    
    public function isTransitionAllowed(self $newStatus): bool
    {
        return match($this) {
            self::PENDING => in_array($newStatus, [self::CONFIRMED, self::CANCELLED]),
            self::CONFIRMED => in_array($newStatus, [self::COMPLETED, self::CANCELLED]),
            self::COMPLETED => false,
            self::CANCELLED => false,
        };
    }
}