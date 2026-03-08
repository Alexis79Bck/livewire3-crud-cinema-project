<?php

namespace App\Domain\Catalog\ValueObjects;

use DateTimeImmutable;

/**
 * Value Object que representa la fecha de estreno de una película.
 *
 * Encapsula la fecha de estreno de una película utilizando DateTimeImmutable
 * para garantizar inmutabilidad y facilitar operaciones de comparación y formato.
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 */
class ReleaseDate
{

    public function __construct(private DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }
}
