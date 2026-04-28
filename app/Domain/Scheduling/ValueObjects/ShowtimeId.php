<?php

namespace App\Domain\Scheduling\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa el identificador único de un showtime.
 */
final class ShowtimeId
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw DomainException::emptyShowtimeId();
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(ShowtimeId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
