<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción específica del dominio que se lanza cuando la sinopsis de una película es inválida.
 *
 * Esta excepción se utiliza en el Value Object Plot cuando se intenta crear
 * una sinopsis vacía o que excede los 500 caracteres permitidos.
 *
 * @see \App\Domain\Catalog\ValueObjects\Plot
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */
final class InvalidMoviePlot extends DomainException
{
    public static function empty(): self
    {
        return new self('The Plot cannot be empty.');
    }

    public static function tooLong(int $max): self
    {
        return new self("The Plot cannot exceed {$max} characters.");
    }
}
