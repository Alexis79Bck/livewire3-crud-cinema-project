<?php

/**
 * Handler (Manejador) para el comando CreateMovieCommand.
 *
 * Este handler procesa el comando de creación de películas, coordinando la
 * generación del ID, la creación del aggregate Movie y su persistencia.
 *
 * El flujo de ejecución es:
 * 1. Generar un identificador único para la película usando IdGenerator
 * 2. Crear los Value Objects a partir de los datos del comando
 * 3. Crear el aggregate Movie usando el factory method create
 * 4. Persistir la película usando el MovieRepository
 *
 * Este handler implementa el patrón Mediator/Handler de CQRS, aislando
 * la lógica de aplicación de las reglas de dominio.
 *
 * @see CreateMovieCommand Comando que trigger esta acción
 * @see MovieRepository Interfaz para persistir la película
 * @see IdGenerator Interfaz para generar identificadores únicos
 * @see Movie Aggregate Root de la película
 */


namespace App\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\Commands\CreateMovieCommand;
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
