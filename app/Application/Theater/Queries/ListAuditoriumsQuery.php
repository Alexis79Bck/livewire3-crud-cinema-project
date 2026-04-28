<?php

/**
 * Query para obtener una lista de auditorios según criterios específicos.
 *
 * Este query representa una solicitud de lectura para recuperar múltiples
 * auditorios del sistema. Forma parte del patrón CQRS (Command Query
 * Responsibility Segregation) que separa las operaciones de lectura de las
 * de escritura.
 *
 * El query puede filtrar los resultados por:
 * - Status: Filtrar por estado específico de los auditorios
 * - Limit: Limitar el número de resultados
 * - Offset: Desplazamiento para paginación
 *
 * Este patrón permite:
 * - Separar claramente las operaciones de lectura y escritura
 * - Optimizar las consultas de lectura
 * - Facilitar el caching de resultados
 * - Mejorar la mantenibilidad del código
 *
 * @see \App\Application\Theater\Handlers\ListAuditoriumsHandler Handler que procesa este query
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository Repositorio utilizado
 */

namespace App\Application\Theater\Queries;

final class ListAuditoriumsQuery
{
    public function __construct(
        private ?string $status = null,
        private int $limit = 50,
        private int $offset = 0
    ) {}

    /**
     * Retorna el estado por el cual filtrar los auditorios.
     */
    public function status(): ?string
    {
        return $this->status;
    }

    /**
     * Retorna el límite de resultados.
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * Retorna el desplazamiento para paginación.
     */
    public function offset(): int
    {
        return $this->offset;
    }
}