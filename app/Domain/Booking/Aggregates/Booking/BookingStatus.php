<?php

namespace App\Domain\Booking\Aggregates\Booking;

/**
 * Value Object que representa el estado de una reserva.
 *
 * Define el ciclo de vida de una reserva:
 * - PENDING: Reserva creada pero no confirmada
 * - CONFIRMED: Reserva confirmada y pagada
 * - CANCELLED: Reserva cancelada
 * - EXPIRED: Reserva que expiró sin confirmar
 * - COMPLETED: Reserva completada (la función ya se llevó a cabo)
 */
enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::CONFIRMED => 'Confirmada',
            self::CANCELLED => 'Cancelada',
            self::EXPIRED => 'Expirada',
            self::COMPLETED => 'Completada',
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this === self::CONFIRMED;
    }

    public function isFinalized(): bool
    {
        return in_array($this, [self::CANCELLED, self::COMPLETED, self::EXPIRED]);
    }
}
