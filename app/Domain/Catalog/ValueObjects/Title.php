<?php

/**
 * Value Object que representa el título de una película.
 *
 * Encapsula el título de una película, asegurando que:
 * - No esté vacío
 * - No exceda los 255 caracteres
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 *
 * @see InvalidMovieTitle Excepción lanzada cuando el título es inválido
 */

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieTitle;

final class Title
{
    private const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw InvalidMovieTitle::empty();
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw InvalidMovieTitle::tooLong(self::MAX_LENGTH);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
