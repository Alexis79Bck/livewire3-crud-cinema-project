<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

class InvalidMovieStatus extends DomainException
{
    public static function published(): self
    {
        return new self('Movie already published.');
    }
}
