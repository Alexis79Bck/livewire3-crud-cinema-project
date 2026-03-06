<?php

namespace App\Domain\Catalog\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

final class InvalidMovieImage extends DomainException
{
    public static function invalidUrl(): self
    {
        return new self('Image URL is invalid.');
    }
}
