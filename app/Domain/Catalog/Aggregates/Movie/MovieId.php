<?php

namespace App\Domain\Catalog\Aggregates\Movies;

use App\Domain\Catalog\Exceptions\InvalidMovieId;


final class MovieId
{
    private string $value;

    public function __construct(?string $value = null)
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
