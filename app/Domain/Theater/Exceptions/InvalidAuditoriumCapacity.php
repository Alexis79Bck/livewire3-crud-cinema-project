<?php

/**
 * Excepción que se lanza cuando se intenta crear un auditorio con una capacidad inválida.
 *
 * La capacidad de un auditorio debe cumplir ciertos criterios:
 * - Valor mínimo de 1 asiento
 * - Valor máximo de 1000 asientos
 */

namespace App\Domain\Theater\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidAuditoriumCapacity extends DomainException
{
    public static function invalid(int $capacity): self
    {
        return new self("Auditorium capacity {$capacity} is invalid. Must be between 1 and 1000");
    }

    public static function negative(): self
    {
        return new self('Auditorium capacity cannot be negative');
    }

    public static function zero(): self
    {
        return new self('Auditorium capacity cannot be zero');
    }

    public static function tooLarge(int $maxCapacity = 1000): self
    {
        return new self("Auditorium capacity cannot exceed {$maxCapacity} seats");
    }
}