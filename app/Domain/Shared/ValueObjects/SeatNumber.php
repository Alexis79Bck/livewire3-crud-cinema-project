<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa la ubicación de un asiento.
 *
 * Encapsula la fila (row) y el número (number) de un asiento,
 * asegurando que:
 * - La fila no esté vacía
 * - El número no sea negativo
 *
 * Esta clase es inmutable: una vez creada, sus valores no pueden cambiar.
 * Proporciona métodos para comparar ubicaciones de asientos.
 */
final class SeatNumber
{
    public function __construct(
        private string $row,
        private int $number
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (trim($this->row) === '') {
            throw DomainException::invalidSeatRow();
        }

        if ($this->number <= 0) {
            throw DomainException::invalidSeatNumber($this->number);
        }
    }

    public function row(): string
    {
        return $this->row;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function equals(SeatNumber $other): bool
    {
        return $this->row === $other->row && $this->number === $other->number;
    }

    public function __toString(): string
    {
        return sprintf('%s-%d', $this->row, $this->number);
    }
}
