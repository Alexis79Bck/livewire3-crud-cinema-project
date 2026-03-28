<?php

/**
 * Servicio de dominio para operaciones avanzadas del catálogo de películas.
 *
 * Este servicio encapsula lógica de negocio que no pertenece a un solo aggregate
 * y que involucra múltiples operaciones o reglas de negocio complejas. Proporciona
 * funcionalidades avanzadas de búsqueda y gestión del catálogo.
 *
 * Responsabilidades del servicio:
 * - Búsquedas avanzadas con múltiples criterios
 * - Validaciones de negocio complejas
 * - Operaciones que involucran múltiples aggregates
 * - Lógica de negocio que no encaja en un aggregate específico
 *
 * Ejemplos de uso:
 * - Buscar películas por múltiples criterios (título, estado, rango de fechas)
 * - Validar si una película puede ser eliminada
 * - Obtener estadísticas del catálogo
 * - Operaciones de batch sobre múltiples películas
 *
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie Aggregate principal
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 */

namespace App\Domain\Catalog\Services;

use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Enums\MovieStatus;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Catalog\ValueObjects\MovieId;

final class MovieCatalogService
{
    public function __construct(
        private MovieRepository $movieRepository
    ) {}

    /**
     * Busca películas por múltiples criterios.
     *
     * @param array<string, mixed> $criteria Criterios de búsqueda
     * @return Movie[] Array de películas que cumplen los criterios
     */
    public function searchMovies(array $criteria): array
    {
        $movies = $this->movieRepository->findAll();

        return array_filter($movies, function (Movie $movie) use ($criteria) {
            // Filtrar por estado si se especifica
            if (isset($criteria['status']) && $movie->status() !== $criteria['status']) {
                return false;
            }

            // Filtrar por título si se especifica (búsqueda parcial)
            if (isset($criteria['title'])) {
                $searchTitle = strtolower($criteria['title']);
                $movieTitle = strtolower($movie->title()->value());
                if (strpos($movieTitle, $searchTitle) === false) {
                    return false;
                }
            }

            // Filtrar por rango de fechas si se especifica
            if (isset($criteria['release_date_from'])) {
                $releaseDate = $movie->releaseDate()->value();
                $fromDate = new \DateTime($criteria['release_date_from']);
                if ($releaseDate < $fromDate) {
                    return false;
                }
            }

            if (isset($criteria['release_date_to'])) {
                $releaseDate = $movie->releaseDate()->value();
                $toDate = new \DateTime($criteria['release_date_to']);
                if ($releaseDate > $toDate) {
                    return false;
                }
            }

            // Filtrar por rating si se especifica
            if (isset($criteria['rating']) && $movie->rating()->value() !== $criteria['rating']) {
                return false;
            }

            return true;
        });
    }

    /**
     * Verifica si una película puede ser eliminada.
     *
     * Una película no puede ser eliminada si:
     * - Está en estado PUBLISHED
     * - Tiene reservas activas (esto requeriría integración con Booking context)
     *
     * @param MovieId $movieId ID de la película a verificar
     * @return bool True si la película puede ser eliminada
     */
    public function canDeleteMovie(MovieId $movieId): bool
    {
        $movie = $this->movieRepository->findById($movieId);

        if ($movie === null) {
            return false;
        }

        // Una película publicada no puede ser eliminada
        if ($movie->status() === MovieStatus::PUBLISHED) {
            return false;
        }

        // Aquí se podría agregar lógica adicional para verificar
        // si hay reservas activas, funciones programadas, etc.

        return true;
    }

    /**
     * Obtiene estadísticas del catálogo.
     *
     * @return array<string, int> Estadísticas del catálogo
     */
    public function getCatalogStatistics(): array
    {
        $allMovies = $this->movieRepository->findAll();

        $statistics = [
            'total' => count($allMovies),
            'draft' => 0,
            'published' => 0,
            'archived' => 0,
        ];

        foreach ($allMovies as $movie) {
            switch ($movie->status()) {
                case MovieStatus::DRAFT:
                    $statistics['draft']++;
                    break;
                case MovieStatus::PUBLISHED:
                    $statistics['published']++;
                    break;
                case MovieStatus::ARCHIVED:
                    $statistics['archived']++;
                    break;
            }
        }

        return $statistics;
    }

    /**
     * Obtiene películas próximas a estrenarse.
     *
     * @param int $days Número de días hacia adelante para buscar
     * @return Movie[] Array de películas próximas a estrenarse
     */
    public function getUpcomingMovies(int $days = 30): array
    {
        $today = new \DateTime();
        $futureDate = (new \DateTime())->modify("+{$days} days");

        $movies = $this->movieRepository->listByDateRange($today, $futureDate);

        return array_filter($movies, function (Movie $movie) {
            return $movie->status() === MovieStatus::PUBLISHED;
        });
    }

    /**
     * Verifica si existe una película con el mismo título.
     *
     * @param string $title Título a verificar
     * @param MovieId|null $excludeId ID de película a excluir de la búsqueda
     * @return bool True si existe una película con el mismo título
     */
    public function movieWithTitleExists(string $title, ?MovieId $excludeId = null): bool
    {
        $movies = $this->movieRepository->findAll();

        foreach ($movies as $movie) {
            // Excluir la película especificada si se proporciona
            if ($excludeId !== null && $movie->id()->equals($excludeId)) {
                continue;
            }

            if (strtolower($movie->title()->value()) === strtolower($title)) {
                return true;
            }
        }

        return false;
    }
}
