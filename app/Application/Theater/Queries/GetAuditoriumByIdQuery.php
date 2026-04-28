<?php

/**
 * Query para obtener un auditorio por su identificador único.
 *
 * Este query representa una solicitud de lectura para recuperar los datos
 * de un auditorio específico utilizando su identificador único. Forma parte
 * del patrón CQRS (Command Query Responsibility Segregation) que separa
 * las operaciones de lectura de las de escritura.
 *
 * El query contiene únicamente los datos necesarios para ejecutar la consulta:
 * - Identificador del auditorio a buscar
 *
 * Este patrón permite:
 * - Separar claramente las operaciones de lectura y escritura
 * - Optimizar las consultas de lectura
 * - Facilitar el caching de resultados
 * - Mejorar la mantenibilidad del código
 *
 * @see \App\Application\Theater\Handlers\GetAuditoriumByIdHandler Handler que procesa este query
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository Repositorio utilizado
 */

namespace App\Application\Theater\Queries;

final class GetAuditoriumByIdQuery
{
    public function __construct(
        private string $auditoriumId
    ) {}

    /**
     * Retorna el identificador del auditorio a buscar.
     */
    public function auditoriumId(): string
    {
        return $this->auditoriumId;
    }
}