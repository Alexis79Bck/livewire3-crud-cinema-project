<?php

/**
 * Value Object que representa la clasificación de edad de una película.
 *
 * Encapsula la clasificación de edad de una película según las regulaciones cinematográficas.
 * Las clasificaciones permitidas son: G, PG, PG-13, R, NC-17.
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 * Proporciona métodos para comparar clasificaciones y convertir a string.
 *
 * @see InvalidMovieRating Excepción lanzada cuando la clasificación no es válida
 */

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieRating;

final class Rating
{
    private const ALLOWED = [
        'G',
        'PG',
        'PG-13',
        'R',
        'NC-17'
    ];

    public function __construct(private string $value)
    {
        $value = strtoupper(trim($value));

        if (! $this->isAllowed($value)) {
            throw InvalidMovieRating::notAllowed();
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function isAllowed(string $value): bool
    {
        return in_array($value, self::ALLOWED, true);
    }
}
