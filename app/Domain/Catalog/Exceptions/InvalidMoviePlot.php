<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidMoviePlot extends DomainException
{
    public static function empty(): self
    {
        return new self('The Plot cannot be empty.');
    }

    public static function tooLong(int $max): self
    {
        return new self("The Plot cannot exceed {$max} characters.");
    }
}
