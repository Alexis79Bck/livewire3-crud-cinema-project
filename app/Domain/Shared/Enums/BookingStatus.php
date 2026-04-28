<?php

namespace App\Domain\Shared\Enums;

/**
 * Enum que representa los posibles estados de una reserva (booking).
 *
 * Define el ciclo de vida de una reserva:
 * - PENDING: Reserva creada pero no confirmada, en espera de pago
 * - CONFIRMED: Reserva confirmada y pagada, tickets emitidos
 * - CANCELLED: Reserva cancelada antes o después de la confirmación
 * - EXPIRED: Reserva que no fue confirmada antes de la fecha de expiración
 * - COMPLETED: Reserva completada (la función ya se llevó a cabo)
 */
enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case COMPLETED = 'completed';

    /**
     * Retorna una etiqueta legible para el estado.
     */
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

    /**
     * Verifica si la reserva puede ser cancelada.
     */
    public function isCancellable(): bool
    {
        return in_array($this, [self::PENDING, self::CONFIRMED]);
    }

    /**
     * Verifica si la reserva está en estado pendiente.
     */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    /**
     * Verifica si la reserva está confirmada.
     */
    public function isConfirmed(): bool
    {
        return $this === self::CONFIRMED;
    }

    /**
     * Verifica si la reserva ya está finalizada (cancelada o completada).
     */
    public function isFinalized(): bool
    {
        return in_array($this, [self::CANCELLED, self::COMPLETED, self::EXPIRED]);
    }
}
