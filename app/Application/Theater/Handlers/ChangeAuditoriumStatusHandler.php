<?php

/**
 * Handler para procesar el comando ChangeAuditoriumStatusCommand.
 *
 * Este handler implementa la lógica para cambiar el estado de un auditorio.
 * Forma parte del patrón CQRS y se encarga de:
 * - Recibir el comando ChangeAuditoriumStatusCommand
 * - Utilizar el repositorio para buscar el auditorio
 * - Cambiar el estado del auditorio
 * - Persistir los cambios
 *
 * El handler sigue el principio de responsabilidad única y solo
 * se encarga de esta operación específica de cambio de estado.
 *
 * @see \App\Application\Theater\Commands\ChangeAuditoriumStatusCommand Comando que procesa
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository Repositorio utilizado
 * @see \App\Domain\Theater\Aggregates\Auditorium\Auditorium Aggregate que se modifica
 */

namespace App\Application\Theater\Handlers;

use App\Application\Theater\Commands\ChangeAuditoriumStatusCommand;
use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;
use App\Domain\Theater\Exceptions\AuditoriumNotFoundException;

final class ChangeAuditoriumStatusHandler
{
    public function __construct(
        private AuditoriumRepository $auditoriumRepository
    ) {}

    /**
     * Ejecuta el comando para cambiar el estado de un auditorio.
     *
     * @param ChangeAuditoriumStatusCommand $command Comando con el ID del auditorio y el nuevo estado
     * @throws AuditoriumNotFoundException Si el auditorio no existe
     */
    public function handle(ChangeAuditoriumStatusCommand $command): void
    {
        $auditoriumId = AuditoriumId::create($command->id);
        $auditorium = $this->auditoriumRepository->findById($auditoriumId);

        if ($auditorium === null) {
            throw AuditoriumNotFoundException::withId($auditoriumId);
        }

        $newStatus = AuditoriumStatus::from($command->status);
        $auditorium->changeStatus($newStatus);

        $this->auditoriumRepository->save($auditorium);
    }
}