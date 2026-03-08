<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción específica del dominio que se lanza cuando el estado de una película es inválido para una operación.
 *
 * Esta excepción se utiliza en el Aggregate Movie cuando se intenta realizar
 * una operación que no es válida para el estado actual de la película.
 * Por ejemplo, intentar publicar una película que ya está publicada.
 *
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie
 * @see \App\Domain\Catalog\Enums\MovieStatus
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */
class InvalidMovieStatus extends DomainException
{
    public static function published(): self
    {
        return new self('Movie already published.');
    }
}
