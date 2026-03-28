<?php

/**
 * Handler para procesar el query ListMoviesByStatusQuery.
 *
 * Este handler implementa la lógica para recuperar películas filtradas
 * por su estado. Forma parte del patrón CQRS y se encarga de:
 * - Recibir el query ListMoviesByStatusQuery
 * - Utilizar el repositorio para buscar películas por estado
 * - Convertir los objetos de dominio a DTOs
 * - Retornar un array con los resultados
 *
 * El handler sigue el principio de responsabilidad única y solo
 * se encarga de la lógica de consulta, sin modificar el estado del sistema.
 *
 * @see \App\Application\Catalog\Movie\Queries\ListMoviesByStatusQuery Query que procesa
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 * @see \App\Application\Catalog\Movie\DTOs\MovieDTO DTO de resultado
 */

namespace App\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\DTOs\MovieDTO;
use App\Application\Catalog\Movie\Queries\ListMoviesByStatusQuery;
use App\Domain\Catalog\Repositories\MovieRepository;

final class ListMoviesByStatusHandler
{
    public function __construct(
        private MovieRepository $movieRepository
    ) {}

    /**
     * Ejecuta el query para listar películas por estado.
     *
     * @param ListMoviesByStatusQuery $query Query con el estado y parámetros de paginación
     * @return MovieDTO[] Array de DTOs con los datos de las películas
     */
    public function handle(ListMoviesByStatusQuery $query): array
    {
        // Por ahora usamos listByDateRange con un rango amplio y filtramos por estado
        // En una implementación completa, se agregaría un método findByStatus() al repositorio
        $startDate = new \DateTime('1900-01-01');
        $endDate = new \DateTime('2100-12-31');
        
        $movies = $this->movieRepository->listByDateRange($startDate, $endDate);

        // Filtrar por estado
        $filteredMovies = array_filter(
            $movies,
            fn ($movie) => $movie->status() === $query->status()
        );

        $movieDTOs = array_map(
            fn ($movie) => MovieDTO::fromDomain($movie),
            $filteredMovies
        );

        // Aplicar paginación si se especifica
        if ($query->offset() !== null || $query->limit() !== null) {
            $offset = $query->offset() ?? 0;
            $limit = $query->limit() ?? count($movieDTOs);
            $movieDTOs = array_slice($movieDTOs, $offset, $limit);
        }

        return $movieDTOs;
    }
}
