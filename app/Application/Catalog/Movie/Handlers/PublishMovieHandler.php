<?php

namespace App\Aplication\Catalog\Movie\Handlers;

use App\Aplication\Catalog\Movie\Commands\PublishMovieCommand;
use App\Application\Catalog\Movie\Exceptions\MovieNotFoundException;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Catalog\ValueObjects\MovieId;

class PublishMovieHandler
{
    public function __construct(
        private MovieRepository $repository
    ) {}

    public function handle(PublishMovieCommand $command): void
    {
        $movie = $this->repository->findById(
            new MovieId($command->movieId)
        );

        if (!$movie) {
            throw new MovieNotFoundException($command->movieId);
        }

        $movie->publish();

        $this->repository->save($movie);
    }
}
