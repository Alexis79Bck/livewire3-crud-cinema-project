<?php

namespace App\Domain\Scheduling\ValueObjects;

/**
 * Value Object que representa el estado de un showtime.
 */
enum ShowtimeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Activa',
            self::INACTIVE => 'Inactiva',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
