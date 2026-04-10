<?php

/**
 * Excepción que se lanza cuando se intenta crear un auditorio con un Id inválido.
 *
 * El Id de un auditorio debe cumplir ciertos criterios:
 * - Debe ser un UUID válido
 */

namespace App\Domain\Theater\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidAuditoriumId extends DomainException
{
    public static function formatInvalid(): self
    {
        return new self('Invalid auditorium ID format');
    }
}