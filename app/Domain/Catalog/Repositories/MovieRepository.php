<?php

namespace App\Domain\Catalog\Repositories;

use App\Domain\Catalog\Aggregates\Movies\Movie;
use App\Domain\Catalog\Aggregates\Movies\MovieId;

interface MovieRepository
{
    public function save(Movie $movie): void;

    public function findById(MovieId $id): ?Movie;

    public function delete(Movie $movie): void;
}
