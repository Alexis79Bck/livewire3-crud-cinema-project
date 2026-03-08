<?php

/**
 * Value Object que representa la sinopsis o trama de una película.
 *
 * Encapsula la descripción de la trama de una película, asegurando que:
 * - No esté vacía
 * - No exceda los 500 caracteres
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 *
 * @see InvalidMoviePlot Excepción lanzada cuando la trama es inválida
 */

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMoviePlot;

final class Plot
{
    private const MAX_LENGTH = 500;

    public function __construct(private string $value)
    {
        $value = trim($value);

        if ($this->isEmpty($value)) {
            throw InvalidMoviePlot::empty();
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw InvalidMoviePlot::tooLong(self::MAX_LENGTH);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    private function isEmpty(string $value): bool
    {
        return $value === '';
    }
}
