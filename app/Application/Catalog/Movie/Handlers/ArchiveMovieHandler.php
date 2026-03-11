<?php

/**
 * Handler (Manejador) para el comando ArchiveMovieCommand.
 *
 * Este handler procesa el comando de archivado de peliculas, coordinando
 * la busqueda de la pelicula en el repositorio, la ejecucion del metodo
 * de archivado en el aggregate y la persistencia de los cambios.
 *
 * El flujo de ejecucion es:
 * 1. Buscar la pelicula por su ID en el repositorio
 * 2. Verificar que la pelicula existe (lanzar excepcion si no)
 * 3. Ejecutar el metodo archive() en el aggregate Movie
 * 4. Persistir los cambios usando el MovieRepository
 *
 * Este handler implementa el patron Mediator/Handler de CQRS, aislando
 * la logica de aplicacion de las reglas de dominio.
 *
 * @see \App\Application\Catalog\Movie\Commands\ArchiveMovieCommand Comando que trigger esta accion
 * @see \App\Domain\Catalog\Repositories\MovieRepository Interfaz para persistir la pelicula
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie Aggregate Root de la pelicula
 */

namespace App\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\Commands\ArchiveMovieCommand;
use App\Application\Catalog\Movie\Exceptions\MovieNotFoundException;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Catalog\ValueObjects\MovieId;

class ArchiveMovieHandler
{
    public function __construct(
        private MovieRepository $repository
    ) {}

    public function handle(ArchiveMovieCommand $command): void
    {
        $movie = $this->repository->findById(
            new MovieId($command->movieId)
        );

        if (!$movie) {
            throw new MovieNotFoundException($command->movieId);
        }

        $movie->archive();

        $this->repository->save($movie);
    }
}
