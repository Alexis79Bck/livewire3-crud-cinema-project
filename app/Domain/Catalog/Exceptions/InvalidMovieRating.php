<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidMovieRating extends DomainException
{
    public static function notAllowed(): self
    {
        return new self('Movie rating not allowed.');
    }
}
