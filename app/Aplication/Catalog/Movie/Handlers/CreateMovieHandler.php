<?php

namespace App\Aplication\Catalog\Movie\Handlers;

use App\Aplication\Catalog\Movie\Commands\CreateMovieCommand;
use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Image;
use App\Domain\Catalog\ValueObjects\Plot;
use App\Domain\Catalog\ValueObjects\Rating;
use App\Domain\Catalog\ValueObjects\ReleaseDate;
use App\Domain\Catalog\ValueObjects\Title;
use App\Domain\Shared\Generator\IdGenerator;

class CreateMovieHandler
{
    public function __construct(
        private MovieRepository $repository,
        private IdGenerator $idGenerator
    ) {}

    public function handle(CreateMovieCommand $command): void
    {
        $movie = Movie::create(
            new MovieId($this->idGenerator->generate()),
            new Title($command->title),
            new Plot($command->plot),
            new ReleaseDate($command->releaseDate),
            new Rating($command->rating),
            new Image($command->image)
        );

        $this->repository->save($movie);
    }
}
