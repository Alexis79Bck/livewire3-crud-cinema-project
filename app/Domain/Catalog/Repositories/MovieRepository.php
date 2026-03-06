<?php

namespace App\Domain\Catalog\Repositories;

use App\Domain\Catalog\Aggregates\Movies\Movie;
use App\Domain\Catalog\Exceptions\InvalidMovieId;

interface MovieRepository
{
    public function save(Movie $movie): void;

    public function findById(InvalidMovieId $id): ?Movie;

    public function delete(Movie $movie): void;
}
