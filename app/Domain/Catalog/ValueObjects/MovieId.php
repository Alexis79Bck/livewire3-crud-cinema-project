<?php

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieId;

/**
 * Value Object que representa el identificador único de una película.
 *
 * Encapsula el ID único de una película, asegurando que no sea una cadena vacía.
 * Proporciona métodos para comparar identificadores y convertir a string.
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 *
 * @see InvalidMovieId Excepción lanzada cuando el ID está vacío
 */
final class MovieId
{

    public function __construct(private ?string $value = null)
    {
        if ($this->isEmpty($value)) {
            throw InvalidMovieId::empty();
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(MovieId $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function isEmpty(string $value): bool
    {
        return $value === '';
    }
}
