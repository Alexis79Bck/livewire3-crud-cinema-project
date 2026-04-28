<?php

namespace App\Domain\Booking\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa el identificador único de una reserva.
 */
final class BookingId
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw DomainException::emptyBookingId();
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(BookingId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
