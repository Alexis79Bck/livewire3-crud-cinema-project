<?php

/**
 * Excepción que se lanza cuando se intenta crear un auditorio con un nombre inválido.
 *
 * El nombre de un auditorio debe cumplir ciertos criterios:
 * - Longitud mínima de 2 caracteres
 * - Longitud máxima de 100 caracteres
 * - No puede estar vacío
 */

namespace App\Domain\Theater\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidAuditoriumName extends DomainException
{
    public static function empty(): self
    {
        return new self('Auditorium name cannot be empty');
    }

    public static function tooShort(int $minLength = 2): self
    {
        return new self("Auditorium name must be at least {$minLength} characters long");
    }

    public static function tooLong(int $maxLength = 100): self
    {
        return new self("Auditorium name cannot exceed {$maxLength} characters");
    }
}