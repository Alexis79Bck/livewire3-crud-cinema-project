<?php

/**
 * Handler para procesar el query ListMoviesQuery.
 *
 * Este handler implementa la lógica para recuperar todas las películas
 * del catálogo. Forma parte del patrón CQRS y se encarga de:
 * - Recibir el query ListMoviesQuery
 * - Utilizar el repositorio para buscar todas las películas
 * - Convertir los objetos de dominio a DTOs
 * - Retornar un array con los resultados
 *
 * El handler sigue el principio de responsabilidad única y solo
 * se encarga de la lógica de consulta, sin modificar el estado del sistema.
 *
 * @see \App\Application\Catalog\Movie\Queries\ListMoviesQuery Query que procesa
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 * @see \App\Application\Catalog\Movie\DTOs\MovieDTO DTO de resultado
 */

namespace App\Application\Catalog\Movie\Handlers;

use App\Application\Catalog\Movie\DTOs\MovieDTO;
use App\Application\Catalog\Movie\Queries\ListMoviesQuery;
use App\Domain\Catalog\Repositories\MovieRepository;

final class ListMoviesHandler
{
    public function __construct(
        private MovieRepository $movieRepository
    ) {}

    /**
     * Ejecuta el query para listar todas las películas.
     *
     * @param ListMoviesQuery $query Query con parámetros de paginación y ordenamiento
     * @return MovieDTO[] Array de DTOs con los datos de las películas
     */
    public function handle(ListMoviesQuery $query): array
    {
        // Por ahora usamos listByDateRange con un rango amplio
        // En una implementación completa, se agregaría un método findAll() al repositorio
        $startDate = new \DateTime('1900-01-01');
        $endDate = new \DateTime('2100-12-31');
        
        $movies = $this->movieRepository->listByDateRange($startDate, $endDate);

        $movieDTOs = array_map(
            fn ($movie) => MovieDTO::fromDomain($movie),
            $movies
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
