<?php

namespace KaayDem\Models\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DRIVER = 'driver';
    case PASSENGER = 'passenger';
    case BOTH = 'both';
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur',
            self::DRIVER => 'Conducteur',
            self::PASSENGER => 'Passager',
            self::BOTH => 'Conducteur et Passager',
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