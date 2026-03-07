<?php

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
