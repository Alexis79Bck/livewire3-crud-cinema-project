<?php

/**
 * Handler para procesar el query GetMovieByIdQuery.
 *
 * Este handler implementa la lógica para recuperar una película por su
 * identificador único. Forma parte del patrón CQRS y se encarga de:
 * - Recibir el query GetMovieByIdQuery
 * - Utilizar el repositorio para buscar la película
 * - Convertir el objeto de dominio a DTO
 * - Retornar el resultado o null si no existe
 *
 * El handler sigue el principio de responsabilidad única y solo
 * se encarga de la lógica de consulta, sin modificar el estado del sistema.
 *
 * @see \App\Application\Catalog\Movie\Queries\GetMovieByIdQuery Query que procesa
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 * @see \App\Application\Catalog\Movie\DTOs\MovieDTO DTO de resultado
 */

namespace App\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\DTOs\MovieDTO;
use App\Application\Catalog\Movie\Queries\GetMovieByIdQuery;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Catalog\ValueObjects\MovieId;

final class GetMovieByIdHandler
{
    public function __construct(
        private MovieRepository $movieRepository
    ) {}

    /**
     * Ejecuta el query para obtener una película por su ID.
     *
     * @param GetMovieByIdQuery $query Query con el ID de la película
     * @return MovieDTO|null DTO con los datos de la película o null si no existe
     */
    public function handle(GetMovieByIdQuery $query): ?MovieDTO
    {
        $movieId = new MovieId($query->movieId());
        $movie = $this->movieRepository->findById($movieId);

        if ($movie === null) {
            return null;
        }

        return MovieDTO::fromDomain($movie);
    }
}
