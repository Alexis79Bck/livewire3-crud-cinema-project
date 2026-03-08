<?php

/**
 * Excepción específica del dominio que se lanza cuando la imagen de una película es inválida.
 *
 * Esta excepción se utiliza en el Value Object Image cuando se intenta crear
 * una URL de imagen inválida o que no apunta a un formato de imagen permitido.
 *
 * @see \App\Domain\Catalog\ValueObjects\Image
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidMovieImage extends DomainException
{
    public static function invalidUrl(): self
    {
        return new self('Image URL is invalid.');
    }
}
