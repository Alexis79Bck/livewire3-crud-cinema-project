<?php

/**
 * Handler (Manejador) para el comando PublishMovieCommand.
 *
 * Este handler procesa el comando de publicacion de peliculas, coordinando
 * la busqueda de la pelicula en el repositorio, la ejecucion del metodo
 * de publicacion en el aggregate y la persistencia de los cambios.
 *
 * El flujo de ejecucion es:
 * 1. Buscar la pelicula por su ID en el repositorio
 * 2. Verificar que la pelicula existe (lanzar excepcion si no)
 * 3. Ejecutar el metodo publish() en el aggregate Movie
 * 4. Persistir los cambios usando el MovieRepository
 *
 * Este handler implementa el patron Mediator/Handler de CQRS, aislando
 * la logica de aplicacion de las reglas de dominio. La publicacion
 * cambia el estado de la pelicula de DRAFT a PUBLISHED, haciendola
 * visible en el catalogo pubblico.
 *
 * @see \App\Application\Catalog\Movie\Commands\PublishMovieCommand Comando que trigger esta accion
 * @see \App\Domain\Catalog\Repositories\MovieRepository Interfaz para persistir la pelicula
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie Aggregate Root de la pelicula
 */

namespace App\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\Commands\PublishMovieCommand;
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
