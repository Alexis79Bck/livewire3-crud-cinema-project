<?php

namespace App\Domain\Theater\Aggregates\Auditorium\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta crear un auditorium
 * con una capacidad inválida (cero o negativa).
 */
class InvalidAuditoriumCapacity extends DomainException
{
    public static function zeroOrNegative(int $capacity): self
    {
        return new self(
            sprintf('Auditorium capacity must be greater than zero. Given: %d', $capacity)
        );
    }
}
