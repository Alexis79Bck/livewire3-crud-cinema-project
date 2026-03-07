<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

class InvalidMovieId extends DomainException
{
    public static function empty(): self
    {
        return new self('Movie id cannot be empty.');
    }
}
