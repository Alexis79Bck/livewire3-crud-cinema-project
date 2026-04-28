<?php

namespace App\Domain\Theater\Aggregates\Auditorium\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta agregar un asiento
 * que ya existe en el auditorium (mismo row y number).
 */
class DuplicateSeatException extends DomainException
{
    public static function duplicate(string $row, int $number): self
    {
        return new self(
            sprintf('A seat with row "%s" and number %d already exists in this auditorium.', $row, $number)
        );
    }
}
