<?php

namespace App\Domain\Theater\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa el nombre de un auditorium.
 *
 * Encapsula el nombre de una sala de cine, asegurando que:
 * - No esté vacío
 * - No exceda los 255 caracteres
 */
final class AuditoriumName
{
    private const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        $this->validate();
    }

    private function validate(): void
    {
        $value = trim($this->value);
        if ($value === '') {
            throw DomainException::emptyAuditoriumName();
        }
        if (strlen($value) > self::MAX_LENGTH) {
            throw DomainException::auditoriumNameTooLong(self::MAX_LENGTH);
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(AuditoriumName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
