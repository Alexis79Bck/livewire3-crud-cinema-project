<?php

namespace App\Domain\Theater\Aggregates\Auditorium\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta agregar un asiento
 * con una fila vacía o inválida.
 */
class InvalidSeatRow extends DomainException
{
    public static function empty(): self
    {
        return new self('Seat row cannot be empty.');
    }
}
