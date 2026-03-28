<?php

/**
 * Query para listar películas filtradas por estado.
 *
 * Este query representa una solicitud de lectura para recuperar las películas
 * que se encuentran en un estado específico (DRAFT, PUBLISHED, ARCHIVED).
 * Forma parte del patrón CQRS y permite filtrar el catálogo por estado.
 *
 * El query contiene:
 * - Estado de las películas a buscar
 * - Parámetros opcionales de paginación y ordenamiento
 *
 * Este patrón permite:
 * - Separar claramente las operaciones de lectura y escritura
 * - Optimizar las consultas de lectura con filtros específicos
 * - Facilitar el caching de resultados
 * - Mejorar la mantenibilidad del código
 *
 * @see \App\Application\Catalog\Movie\Handlers\ListMoviesByStatusHandler Handler que procesa este query
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 * @see \App\Domain\Catalog\Enums\MovieStatus Enum de estados
 */

namespace App\Application\Catalog\Movie\Queries;

use App\Domain\Catalog\Enums\MovieStatus;

final class ListMoviesByStatusQuery
{
    public function __construct(
        private MovieStatus $status,
        private ?int $limit = null,
        private ?int $offset = null
    ) {}

    /**
     * Retorna el estado de las películas a buscar.
     */
    public function status(): MovieStatus
    {
        return $this->status;
    }

    /**
     * Retorna el límite de resultados a retornar.
     */
    public function limit(): ?int
    {
        return $this->limit;
    }

    /**
     * Retorna el offset para paginación.
     */
    public function offset(): ?int
    {
        return $this->offset;
    }
}
