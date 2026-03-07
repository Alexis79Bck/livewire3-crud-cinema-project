<?php

namespace App\Aplication\Catalog\Movie\Commands;

use DateTimeImmutable;

class CreateMovieCommand
{
    public function __construct(
        public readonly string $title,
        public readonly string $plot,
        public readonly DateTimeImmutable $releaseDate,
        public readonly string $rating,
        public readonly string $image
    ) {}
}
