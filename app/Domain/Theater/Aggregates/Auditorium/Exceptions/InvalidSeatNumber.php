<?php

namespace App\Domain\Theater\Aggregates\Auditorium\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta agregar un asiento
 * con un número inválido (cero o negativo).
 */
class InvalidSeatNumber extends DomainException
{
    public static function invalid(int $number): self
    {
        return new self(
            sprintf('Seat number must be a positive integer. Given: %d', $number)
        );
    }
}
