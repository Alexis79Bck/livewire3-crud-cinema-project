<?php

/**
 * Excepción específica del dominio que se lanza cuando el título de una película es inválido.
 *
 * Esta excepción se utiliza en el Value Object Title cuando se intenta crear
 * un título vacío o que excede los 255 caracteres permitidos.
 *
 * @see \App\Domain\Catalog\ValueObjects\Title
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;


final class InvalidMovieTitle extends DomainException
{
    public static function empty(): self
    {
        return new self('Movie title cannot be empty.');
    }

    public static function tooLong(int $max): self
    {
        return new self("Movie title cannot exceed {$max} characters.");
    }
}
