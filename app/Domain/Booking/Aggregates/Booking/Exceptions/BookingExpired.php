<?php

namespace App\Domain\Booking\Aggregates\Booking\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta operar sobre una reserva expirada.
 */
class BookingExpired extends DomainException
{
    public static function expired(\App\Domain\Booking\ValueObjects\BookingId $bookingId): self
    {
        return new self(sprintf(
            'Booking %s has expired.',
            $bookingId->value()
        ));
    }
}
