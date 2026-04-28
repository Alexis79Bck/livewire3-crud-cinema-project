<?php

namespace App\Domain\Theater\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\ValueObjects\AuditoriumName;

/**
 * Evento de dominio que se dispara cuando se crea un nuevo auditorium.
 *
 * Este evento representa la creación de una nueva sala de cine en el sistema.
 */
final class AuditoriumCreated extends DomainEvent
{
    public function __construct(
        private AuditoriumId $auditoriumId,
        private AuditoriumName $name,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function auditoriumId(): AuditoriumId
    {
        return $this->auditoriumId;
    }

    public function name(): AuditoriumName
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'auditorium_id' => $this->auditoriumId->value(),
            'name' => $this->name->value(),
        ]);
    }
}
