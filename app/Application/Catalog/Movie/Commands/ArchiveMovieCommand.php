<?php

namespace App\Aplication\Catalog\Movie\Commands;

class ArchiveMovieCommand
{
    public function __construct(
        public readonly string $movieId
    ) {}
}
