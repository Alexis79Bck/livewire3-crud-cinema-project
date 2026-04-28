<?php

<<<<<<< HEAD
=======
/**
 * Evento de dominio que representa la creación de un auditorio.
 *
 * Este evento se dispara cuando un nuevo auditorio es creado en el sistema.
 * Contiene toda la información necesaria sobre el auditorio recién creado
 * para permitir la reacción de otros componentes del sistema.
 */

>>>>>>> develop
namespace App\Domain\Theater\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
<<<<<<< HEAD
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
=======

final class AuditoriumCreated extends DomainEvent
{
    public function __construct(
        private readonly AuditoriumId $id,
        private readonly string $name,
        private readonly int $capacity,
        private readonly string $location,
>>>>>>> develop
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

<<<<<<< HEAD
    public function auditoriumId(): AuditoriumId
    {
        return $this->auditoriumId;
    }

    public function name(): AuditoriumName
=======
    public function id(): AuditoriumId
    {
        return $this->id;
    }

    public function name(): string
>>>>>>> develop
    {
        return $this->name;
    }

<<<<<<< HEAD
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'auditorium_id' => $this->auditoriumId->value(),
            'name' => $this->name->value(),
        ]);
    }
}
=======
    public function capacity(): int
    {
        return $this->capacity;
    }

    public function location(): string
    {
        return $this->location;
    }
}
>>>>>>> develop
