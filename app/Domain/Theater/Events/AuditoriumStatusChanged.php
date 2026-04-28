<?php

/**
 * Evento de dominio que representa el cambio de estado de un auditorio.
 *
 * Este evento se dispara cuando un auditorio cambia de estado
 * (activo, mantenimiento, cerrado).
 * Contiene toda la información necesaria sobre el cambio de estado
 * para permitir la reacción de otros componentes del sistema.
 */

namespace App\Domain\Theater\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;

final class AuditoriumStatusChanged extends DomainEvent
{
    public function __construct(
        private readonly AuditoriumId $id,
        private readonly AuditoriumStatus $previousStatus,
        private readonly AuditoriumStatus $newStatus,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function id(): AuditoriumId
    {
        return $this->id;
    }

    public function previousStatus(): AuditoriumStatus
    {
        return $this->previousStatus;
    }

    public function newStatus(): AuditoriumStatus
    {
        return $this->newStatus;
    }
}