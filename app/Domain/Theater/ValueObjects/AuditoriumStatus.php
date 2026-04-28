<?php

namespace App\Domain\Theater\ValueObjects;

/**
 * Value Object que representa el estado de un auditorium.
 *
 * Define los posibles estados de una sala de cine:
 * - ACTIVE: Sala activa y disponible para uso
 * - INACTIVE: Sala inactiva, no disponible para uso
 */
enum AuditoriumStatus: string
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
