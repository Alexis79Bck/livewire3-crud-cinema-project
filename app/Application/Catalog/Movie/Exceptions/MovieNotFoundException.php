<?php

namespace App\Application\Catalog\Movie\Exceptions;

use RuntimeException;

class MovieNotFoundException extends RuntimeException
{
    public function __construct(string $movieId)
    {
        parent::__construct("Movie with id {$movieId} not found.");
    }
}
