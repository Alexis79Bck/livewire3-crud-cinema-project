<?php

/**
 * Handler (Manejador) para el comando CreateAuditoriumCommand.
 *
 * Este handler procesa el comando de creación de auditorios, coordinando la
 * generación del ID, la creación del aggregate Auditorium y su persistencia.
 *
 * El flujo de ejecución es:
 * 1. Generar un identificador único para el auditorio usando IdGenerator
 * 2. Crear el aggregate Auditorium usando el factory method create
 * 3. Persistir el auditorio usando el AuditoriumRepository
 *
 * Este handler implementa el patrón Mediator/Handler de CQRS, aislando
 * la lógica de aplicación de las reglas de dominio.
 *
 * @see CreateAuditoriumCommand Comando que trigger esta acción
 * @see AuditoriumRepository Interfaz para persistir el auditorio
 * @see IdGenerator Interfaz para generar identificadores únicos
 * @see Auditorium Aggregate Root del auditorio
 */


namespace App\Application\Theater\Handlers;

use App\Application\Theater\Commands\CreateAuditoriumCommand;
use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Shared\Generator\IdGenerator;

class CreateAuditoriumHandler
{
    public function __construct(
        private AuditoriumRepository $repository,
        private IdGenerator $idGenerator
    ) {}

    public function handle(CreateAuditoriumCommand $command): void
    {
        $auditorium = Auditorium::create(
            AuditoriumId::create($this->idGenerator->generate()),
            $command->name,
            $command->capacity,
            $command->location
        );

        $this->repository->save($auditorium);
    }
}