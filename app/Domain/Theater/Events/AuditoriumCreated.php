<?php

/**
 * Evento de dominio que representa la creación de un auditorio.
 *
 * Este evento se dispara cuando un nuevo auditorio es creado en el sistema.
 * Contiene toda la información necesaria sobre el auditorio recién creado
 * para permitir la reacción de otros componentes del sistema.
 */

namespace App\Domain\Theater\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;

final class AuditoriumCreated extends DomainEvent
{
    public function __construct(
        private readonly AuditoriumId $id,
        private readonly string $name,
        private readonly int $capacity,
        private readonly string $location,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function id(): AuditoriumId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function location(): string
    {
        return $this->location;
    }
}