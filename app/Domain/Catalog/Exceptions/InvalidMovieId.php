<?php

/**
 * Excepción específica del dominio que se lanza cuando el identificador de una película es inválido.
 *
 * Esta excepción se utiliza en el Value Object MovieId cuando se intenta crear
 * un identificador vacío o nulo.
 *
 * @see \App\Domain\Catalog\ValueObjects\MovieId
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

class InvalidMovieId extends DomainException
{
    public static function empty(): self
    {
        return new self('Movie id cannot be empty.');
    }
}
