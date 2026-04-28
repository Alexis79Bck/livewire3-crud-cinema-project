<?php

namespace App\Domain\Booking\Aggregates\Booking\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se excede el máximo de tickets por reserva.
 */
class MaxTicketsExceeded extends DomainException
{
    public static function limit(int $limit): self
    {
        return new self(sprintf(
            'Maximum number of tickets per booking is %d.',
            $limit
        ));
    }
}
