<?php

namespace App\Domain\Theater\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa el identificador único de un auditorium.
 *
 * Encapsula el ID único de un auditorium, asegurando que no sea una cadena vacía.
 */
final class AuditoriumId
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw DomainException::emptyAuditoriumId();
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(AuditoriumId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
