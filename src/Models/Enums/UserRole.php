<?php

namespace KaayDem\Models\Enums;

class UserRole
{
    const ADMIN = 'admin';
    const DRIVER = 'driver';
    const PASSENGER = 'passenger';
    const BOTH = 'both';
    
    public static function label($role): string
    {
        return match($role) {
            self::ADMIN => 'Administrateur',
            self::DRIVER => 'Conducteur',
            self::PASSENGER => 'Passager',
            self::BOTH => 'Conducteur et Passager',
            default =>'inconnu'
        };
    }
    
    public function canPublish(): bool
    {
        return in_array($this, [self::DRIVER, self::BOTH]);
    }
    
    public function canBook(): bool
    {
        return in_array($this, [self::PASSENGER, self::BOTH]);
    }
}