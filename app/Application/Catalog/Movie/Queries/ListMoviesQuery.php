<?php

/**
 * Query para listar todas las películas del catálogo.
 *
 * Este query representa una solicitud de lectura para recuperar todas las
 * películas disponibles en el catálogo. Forma parte del patrón CQRS y
 * permite obtener una vista completa del catálogo de películas.
 *
 * El query puede incluir parámetros opcionales para:
 * - Paginación (límite y offset)
 * - Ordenamiento (campo y dirección)
 * - Filtros básicos
 *
 * Este patrón permite:
 * - Separar claramente las operaciones de lectura y escritura
 * - Optimizar las consultas de lectura
 * - Facilitar el caching de resultados
 * - Mejorar la mantenibilidad del código
 *
 * @see \App\Application\Catalog\Movie\Handlers\ListMoviesHandler Handler que procesa este query
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 */

namespace App\Application\Catalog\Movie\Queries;

final class ListMoviesQuery
{
    public function __construct(
        private ?int $limit = null,
        private ?int $offset = null,
        private ?string $orderBy = null,
        private ?string $orderDirection = 'ASC'
    ) {}

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

    /**
     * Retorna el campo por el cual ordenar.
     */
    public function orderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * Retorna la dirección de ordenamiento (ASC o DESC).
     */
    public function orderDirection(): string
    {
        return $this->orderDirection;
    }
}
