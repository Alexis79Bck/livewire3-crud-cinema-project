<?php

/**
 * Command para publicar una película.
 *
 * Representa la intención de cambiar el estado de una película
 * de DRAFT a PUBLISHED.
 */

namespace App\Aplication\Catalog\Movie\Commands;

class PublishMovieCommand
{
    public function __construct(
        public readonly string $movieId
    ) {}
}
