<?php

namespace App\Domain\Catalog\ValueObjects;

use DateTimeImmutable;

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
