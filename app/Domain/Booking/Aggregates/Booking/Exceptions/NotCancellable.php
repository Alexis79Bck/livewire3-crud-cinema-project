<?php

namespace App\Domain\Booking\Aggregates\Booking\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta cancelar una reserva
 * que no es cancelable.
 */
class NotCancellable extends DomainException
{
    public static function forBooking(\App\Domain\Booking\ValueObjects\BookingId $bookingId): self
    {
        return new self(sprintf(
            'Booking %s cannot be cancelled at this time.',
            $bookingId->value()
        ));
    }
}
