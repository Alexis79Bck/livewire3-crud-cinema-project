<?php

namespace App\Domain\Catalog\ValueObjects;

use DateTimeImmutable;

class ReleaseDate
{
    private DateTimeImmutable $value;

    public function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }
}
