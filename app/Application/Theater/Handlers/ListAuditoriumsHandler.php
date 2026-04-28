<?php

/**
 * Handler para procesar el query ListAuditoriumsQuery.
 *
 * Este handler implementa la lógica para recuperar una lista de auditorios
 * según los criterios especificados. Forma parte del patrón CQRS y se encarga de:
 * - Recibir el query ListAuditoriumsQuery
 * - Utilizar el repositorio para buscar los auditorios
 * - Convertir los objetos de dominio a DTOs
 * - Retornar el resultado
 *
 * El handler sigue el principio de responsabilidad única y solo
 * se encarga de la lógica de consulta, sin modificar el estado del sistema.
 *
 * @see \App\Application\Theater\Queries\ListAuditoriumsQuery Query que procesa
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository Repositorio utilizado
 * @see \App\Application\Theater\DTOs\AuditoriumDTO DTO de resultado
 */

namespace App\Application\Theater\Handlers;

use App\Application\Theater\DTOs\AuditoriumDTO;
use App\Application\Theater\Queries\ListAuditoriumsQuery;
use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;

final class ListAuditoriumsHandler
{
    public function __construct(
        private AuditoriumRepository $auditoriumRepository
    ) {}

    /**
     * Ejecuta el query para obtener una lista de auditorios.
     *
     * @param ListAuditoriumsQuery $query Query con los criterios de búsqueda
     * @return AuditoriumDTO[] Array de DTOs con los datos de los auditorios
     */
    public function handle(ListAuditoriumsQuery $query): array
    {
        if ($query->status() !== null) {
            $status = AuditoriumStatus::from($query->status());
            $auditoriums = $this->auditoriumRepository->findByStatus($status);
        } else {
            $auditoriums = $this->auditoriumRepository->findAll();
        }

        // Convertir los objetos de dominio a DTOs
        return array_map(
            fn ($auditorium) => AuditoriumDTO::fromDomain($auditorium),
            $auditoriums
        );
    }
}