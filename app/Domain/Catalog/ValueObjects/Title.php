<?php

namespace App\Domain\Catalog\ValueObjects;

use App\Domain\Catalog\Exceptions\InvalidMovieTitle;

final class Title
{
    private string $value;
    private const MAX_LENGTH = 255;

    public function __construct(string $value)
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
