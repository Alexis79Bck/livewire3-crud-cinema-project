<?php

/**
 * Handler para procesar el query GetAuditoriumByIdQuery.
 *
 * Este handler implementa la lógica para recuperar un auditorio por su
 * identificador único. Forma parte del patrón CQRS y se encarga de:
 * - Recibir el query GetAuditoriumByIdQuery
 * - Utilizar el repositorio para buscar el auditorio
 * - Convertir el objeto de dominio a DTO
 * - Retornar el resultado o null si no existe
 *
 * El handler sigue el principio de responsabilidad única y solo
 * se encarga de la lógica de consulta, sin modificar el estado del sistema.
 *
 * @see \App\Application\Theater\Queries\GetAuditoriumByIdQuery Query que procesa
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository Repositorio utilizado
 * @see \App\Application\Theater\DTOs\AuditoriumDTO DTO de resultado
 */

namespace App\Application\Theater\Handlers;

use App\Application\Theater\DTOs\AuditoriumDTO;
use App\Application\Theater\Queries\GetAuditoriumByIdQuery;
use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;

final class GetAuditoriumByIdHandler
{
    public function __construct(
        private AuditoriumRepository $auditoriumRepository
    ) {}

    /**
     * Ejecuta el query para obtener un auditorio por su ID.
     *
     * @param GetAuditoriumByIdQuery $query Query con el ID del auditorio
     * @return AuditoriumDTO|null DTO con los datos del auditorio o null si no existe
     */
    public function handle(GetAuditoriumByIdQuery $query): ?AuditoriumDTO
    {
        $auditoriumId = AuditoriumId::create($query->auditoriumId());
        $auditorium = $this->auditoriumRepository->findById($auditoriumId);

        if ($auditorium === null) {
            return null;
        }

        return AuditoriumDTO::fromDomain($auditorium);
    }
}