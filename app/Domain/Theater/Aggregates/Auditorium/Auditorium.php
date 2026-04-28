<?php

/**
 * Aggregate Root que representa un auditorio en el sistema de cine.
 *
 * Esta clase encapsula toda la información y comportamiento relacionado con un auditorio,
 * incluyendo:
 * - Identificador único (AuditoriumId)
 * - Nombre (string)
 * - Capacidad (int)
 * - Ubicación (string)
 * - Estado (AuditoriumStatus)
 * - Asientos (colección de Seat)
 *
 * El aggregate garantiza la consistencia de los datos y encapsula las reglas de negocio
 * relacionadas con el ciclo de vida de un auditorio (activación, mantenimiento, cierre).
 *
 * Utiliza el patrón de fábrica estático (factory method) para la creación de instancias
 * y el método reconstitute para reconstruir el aggregate desde una fuente de datos persistida.
 *
 * @see AuditoriumStatus Estados posibles del auditorio
 */

namespace App\Domain\Theater\Aggregates\Auditorium;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Events\AuditoriumCreated;
use App\Domain\Theater\Events\AuditoriumStatusChanged;
use App\Domain\Theater\Exceptions\InvalidAuditoriumName;
use App\Domain\Theater\Exceptions\InvalidAuditoriumCapacity;
use App\Domain\Theater\Exceptions\InvalidAuditoriumLocation;

final class Auditorium
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

    /**
     * @var Seat[]
     */
    private array $seats = [];

    private function __construct(
        private readonly AuditoriumId $id,
        private string $name,
        private int $capacity,
        private string $location,
        private AuditoriumStatus $status
    ) {}

    public static function create(
        AuditoriumId $id,
        string $name,
        int $capacity,
        string $location
    ): self {
        // Validate inputs
        if (empty($name)) {
            throw InvalidAuditoriumName::empty();
        }

        if (strlen($name) < 2) {
            throw InvalidAuditoriumName::tooShort();
        }

        if (strlen($name) > 100) {
            throw InvalidAuditoriumName::tooLong();
        }

        if ($capacity < 1) {
            throw InvalidAuditoriumCapacity::invalid($capacity);
        }

        if ($capacity > 1000) {
            throw InvalidAuditoriumCapacity::tooLarge();
        }

        if (empty($location)) {
            throw InvalidAuditoriumLocation::empty();
        }

        if (strlen($location) < 5) {
            throw InvalidAuditoriumLocation::tooShort();
        }

        if (strlen($location) > 255) {
            throw InvalidAuditoriumLocation::tooLong();
        }

        $auditorium = new self(
            $id,
            $name,
            $capacity,
            $location,
            AuditoriumStatus::ACTIVE
        );

        $auditorium->recordEvent(new AuditoriumCreated(
            $id,
            $name,
            $capacity,
            $location,
            [
                'aggregate_id' => $id->toString(),
                'aggregate_type' => 'auditorium'
            ]
        ));

        return $auditorium;
    }

    public static function reconstitute(
        AuditoriumId $id,
        string $name,
        int $capacity,
        string $location,
        AuditoriumStatus $status,
        array $seats = []
    ): self {
        $auditorium = new self(
            $id,
            $name,
            $capacity,
            $location,
            $status
        );

        $auditorium->seats = $seats;

        return $auditorium;
    }

    public function changeStatus(AuditoriumStatus $newStatus): void
    {
        if ($this->status === $newStatus) {
            return; // No change needed
        }

        $previousStatus = $this->status;
        $this->status = $newStatus;

        $this->recordEvent(new AuditoriumStatusChanged(
            $this->id,
            $previousStatus,
            $newStatus,
            [
                'aggregate_id' => $this->id->toString(),
                'aggregate_type' => 'auditorium',
                'previous_status' => $previousStatus->value,
                'new_status' => $newStatus->value
            ]
        ));
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

    public function status(): AuditoriumStatus
    {
        return $this->status;
    }

    /**
     * @return Seat[]
     */
    public function seats(): array
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): void
    {
        // Check if seat already exists
        foreach ($this->seats as $existingSeat) {
            if ($existingSeat->seatNumber()->equals($seat->seatNumber())) {
                throw new \InvalidArgumentException('Seat with this number already exists');
            }
        }

        $this->seats[] = $seat;
    }

    public function removeSeat(SeatNumber $seatNumber): void
    {
        foreach ($this->seats as $key => $seat) {
            if ($seat->seatNumber()->equals($seatNumber)) {
                unset($this->seats[$key]);
                // Re-index array
                $this->seats = array_values($this->seats);
                return;
            }
        }
    }

    /**
     * Registra un evento de dominio para ser publicado posteriormente.
     */
    protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Retorna todos los eventos registrados y los limpia.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    /**
     * Retorna todos los eventos registrados sin limpiarlos.
     *
     * @return DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Limpia todos los eventos registrados.
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }
}
