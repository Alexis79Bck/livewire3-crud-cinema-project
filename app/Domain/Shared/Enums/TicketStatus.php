<?php

namespace App\Domain\Shared\Enums;

/**
 * Enum que representa los posibles estados de un ticket (entrada).
 *
 * Define el ciclo de vida de una entrada:
 * - ACTIVE: Ticket válido y disponible para uso
 * - USED: Ticket ya fue utilizado (escaneado)
 * - CANCELLED: Ticket fue cancelado junto con su reserva
 * - REFUNDED: Ticket fue reembolsado
 */
enum TicketStatus: string
{
    case ACTIVE = 'active';
    case USED = 'used';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    /**
     * Retorna una etiqueta legible para el estado.
     */
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Activo',
            self::USED => 'Usado',
            self::CANCELLED => 'Cancelado',
            self::REFUNDED => 'Reembolsado',
        };
    }

    /**
     * Verifica si el ticket está activo y puede ser utilizado.
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Verifica si el ticket ya fue utilizado.
     */
    public function isUsed(): bool
    {
        return $this === self::USED;
    }

    /**
     * Verifica si el ticket puede ser reembolsado.
     */
    public function isRefundable(): bool
    {
        return in_array($this, [self::ACTIVE, self::CANCELLED]);
    }
}
