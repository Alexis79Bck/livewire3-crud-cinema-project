<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidMovieTitle extends DomainException
{
    public static function empty(): self
    {
        return new self('Movie title cannot be empty.');
    }

    public static function tooLong(int $max): self
    {
        return new self("Movie title cannot exceed {$max} characters.");
    }
}
