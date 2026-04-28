<?php

/**
 * Excepción que se lanza cuando se intenta crear un auditorio con una ubicación inválida.
 *
 * La ubicación de un auditorio debe cumplir ciertos criterios:
 * - Longitud mínima de 5 caracteres
 * - Longitud máxima de 255 caracteres
 */

namespace App\Domain\Theater\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidAuditoriumLocation extends DomainException
{
    public static function empty(): self
    {
        return new self('Auditorium location cannot be empty');
    }

    public static function tooShort(int $minLength = 5): self
    {
        return new self("Auditorium location must be at least {$minLength} characters long");
    }

    public static function tooLong(int $maxLength = 255): self
    {
        return new self("Auditorium location cannot exceed {$maxLength} characters");
    }
}