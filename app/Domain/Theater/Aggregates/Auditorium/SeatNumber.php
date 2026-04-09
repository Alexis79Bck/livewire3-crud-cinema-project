<?php

/**
 * Value Object que representa el número(identificador) de un asiento en un auditorio.
 *
 * Este objeto valor garantiza que los números de asiento sean válidos
 * y proporciona métodos para su manipulación segura.
 *
 * El número de asiento es inmutable una vez creado.
 */

namespace App\Domain\Theater\Aggregates\Auditorium;

use App\Domain\Shared\Exceptions\DomainException;

final class SeatNumber
{
    private string $value;

    private function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new DomainException('Invalid seat number format. Must be alphanumeric with optional row letter.');
        }

        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    private static function isValid(string $value): bool
    {
        // Valid seat format: RowLetter-Number (e.g., A-1, B-15)
        return preg_match('/^[A-Z]\-\d+$/', $value) === 1;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
