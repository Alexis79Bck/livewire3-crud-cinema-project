<?php

/**
 * Excepción específica del dominio que se lanza cuando la clasificación de edad de una película es inválida.
 *
 * Esta excepción se utiliza en el Value Object Rating cuando se intenta crear
 * una clasificación que no está en la lista de clasificaciones permitidas (G, PG, PG-13, R, NC-17).
 *
 * @see \App\Domain\Catalog\ValueObjects\Rating
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidMovieRating extends DomainException
{
    public static function notAllowed(): self
    {
        return new self('Movie rating not allowed.');
    }
}
