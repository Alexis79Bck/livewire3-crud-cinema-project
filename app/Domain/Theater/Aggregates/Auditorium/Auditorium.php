<?php

namespace App\Domain\Theater\Aggregates\Auditorium;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\Exceptions\DuplicateSeatException;
use App\Domain\Theater\Aggregates\Auditorium\Exceptions\InvalidAuditoriumCapacity;
use App\Domain\Theater\Aggregates\Auditorium\Exceptions\InvalidSeatNumber;
use App\Domain\Theater\Aggregates\Auditorium\Exceptions\InvalidSeatRow;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Theater\ValueObjects\AuditoriumName;
use App\Domain\Theater\ValueObjects\AuditoriumStatus;
use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Theater\ValueObjects\SeatType;

/**
 * Aggregate Root que representa un auditorio/sala de cine.
 *
 * Encapsula toda la información y comportamiento relacionado con una sala:
 * - Identificador único (AuditoriumId)
 * - Nombre (AuditoriumName)
 * - Capacidad total
 * - Estado (active/inactive)
 * - Lista de asientos
 *
 * El aggregate garantiza la consistencia de los datos y encapsula las reglas
 * de negocio relacionadas con la gestión de salas y asientos.
 *
 * Utiliza el patrón de fábrica estático (factory method) para la creación de
 * instancias y el método reconstitute para reconstruir el aggregate desde
 * una fuente de datos persistida.
 *
 * @see AuditoriumStatus Estados posibles de la sala
 */
final class Auditorium
{
    /** @var DomainEvent[] */
    private array $events = [];

    /** @var SeatEntity[] */
    private array $seats = [];

    private function __construct(
        private AuditoriumId $id,
        private AuditoriumName $name,
        private int $capacity,
        private AuditoriumStatus $status
    ) {
    }

    /**
     * Crea un nuevo auditorium.
     */
    public static function create(
        AuditoriumId $id,
        AuditoriumName $name,
        int $capacity,
        AuditoriumStatus $status
    ): self {
        if ($capacity <= 0) {
            throw InvalidAuditoriumCapacity::zeroOrNegative($capacity);
        }

        $auditorium = new self($id, $name, $capacity, $status);

        $auditorium->recordEvent(new \App\Domain\Theater\Events\AuditoriumCreated(
            $id,
            $name
        ));

        return $auditorium;
    }

    /**
     * Reconstruye un auditorium desde datos persistidos.
     */
    public static function reconstitute(
        AuditoriumId $id,
        AuditoriumName $name,
        int $capacity,
        AuditoriumStatus $status,
        array $seats = []
    ): self {
        $auditorium = new self($id, $name, $capacity, $status);
        $auditorium->seats = $seats;
        return $auditorium;
    }

    /**
     * Agrega un asiento al auditorium.
     */
    public function addSeat(
        SeatNumber $seatNumber,
        SeatType $seatType
    ): void {
        $this->assertActive();
        $this->assertNotDuplicateSeat($seatNumber);
        $this->assertSeatRowValid($seatNumber);
        $this->assertSeatNumberValid($seatNumber);

        $this->seats[] = new SeatEntity($seatNumber, $seatType);

        $this->recordEvent(new \App\Domain\Theater\Events\SeatAdded(
            $this->id,
            $seatNumber,
            $seatType
        ));
    }

    /**
     * Elimina un asiento del auditorium.
     *
     * @throws \DomainException Si hay reservas que usan este asiento
     */
    public function removeSeat(
        SeatNumber $seatNumber,
        bool $hasBookings = false
    ): void {
        $this->assertActive();

        if ($hasBookings) {
            throw new \DomainException('Cannot remove a seat that has bookings.');
        }

        foreach ($this->seats as $index => $seat) {
            if ($seat->seatNumber()->equals($seatNumber)) {
                unset($this->seats[$index]);
                $this->seats = array_values($this->seats);

                $this->recordEvent(new \App\Domain\Theater\Events\SeatRemoved(
                    $this->id,
                    $seatNumber
                ));

                return;
            }
        }

        throw new \DomainException(sprintf(
            'Seat %s does not exist in this auditorium.',
            $seatNumber->value()
        ));
    }

    /**
     * Actualiza el tipo de un asiento existente.
     */
    public function updateSeatType(
        SeatNumber $seatNumber,
        SeatType $newSeatType
    ): void {
        $this->assertActive();

        foreach ($this->seats as $index => $seat) {
            if ($seat->seatNumber()->equals($seatNumber)) {
                $this->seats[$index] = new SeatEntity($seatNumber, $newSeatType);
                return;
            }
        }

        throw new \DomainException(sprintf(
            'Seat %s does not exist in this auditorium.',
            $seatNumber->value()
        ));
    }

    /**
     * Recupera un asiento por su ubicación.
     */
    public function getSeatAt(SeatNumber $seatNumber): ?SeatEntity
    {
        foreach ($this->seats as $seat) {
            if ($seat->seatNumber()->equals($seatNumber)) {
                return $seat;
            }
        }
        return null;
    }

    /**
     * Obtiene asientos filtrados por tipo.
     *
     * @return SeatEntity[]
     */
    public function getSeatsByType(SeatType $seatType): array
    {
        return array_values(array_filter(
            $this->seats,
            fn (SeatEntity $seat) => $seat->seatType()->equals($seatType)
        ));
    }

    /**
     * Obtiene todos los asientos.
     *
     * @return SeatEntity[]
     */
    public function getAllSeats(): array
    {
        return $this->seats;
    }

    /**
     * Activa la sala.
     */
    public function activate(): void
    {
        if ($this->status->isActive()) {
            return;
        }
        $this->status = AuditoriumStatus::ACTIVE;
    }

    /**
     * Desactiva la sala.
     */
    public function deactivate(): void
    {
        if (!$this->status->isActive()) {
            return;
        }
        $this->status = AuditoriumStatus::INACTIVE;
    }

    /**
     * Obtiene el total de asientos en la sala.
     */
    public function getTotalCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * Obtiene el número de asientos registrados.
     */
    public function getSeatsCount(): int
    {
        return count($this->seats);
    }

    /**
     * Verifica si un asiento existe en la sala.
     */
    public function hasSeat(SeatNumber $seatNumber): bool
    {
        return $this->getSeatAt($seatNumber) !== null;
    }

    /**
     * Verifica si todos los asientos tienen una fila y número únicos.
     */
    public function hasUniqueSeats(): bool
    {
        $seen = [];
        foreach ($this->seats as $seat) {
            $key = $seat->seatNumber()->row() . '-' . $seat->seatNumber()->number();
            if (isset($seen[$key])) {
                return false;
            }
            $seen[$key] = true;
        }
        return true;
    }

    /**
     * Retorna el ID del auditorium.
     */
    public function id(): AuditoriumId
    {
        return $this->id;
    }

    /**
     * Retorna el nombre del auditorium.
     */
    public function name(): AuditoriumName
    {
        return $this->name;
    }

    /**
     * Retorna el estado del auditorium.
     */
    public function status(): AuditoriumStatus
    {
        return $this->status;
    }

    /**
     * Retorna la capacidad del auditorium.
     */
    public function capacity(): int
    {
        return $this->capacity;
    }

    /**
     * Retorna los eventos de dominio registrados.
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
     * Retorna los eventos de dominio registrados sin limpiarlos.
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

    /**
     * Registra un evento de dominio.
     */
    protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    private function assertActive(): void
    {
        if (!$this->status->isActive()) {
            throw new \DomainException('Auditorium is not active.');
        }
    }

    private function assertNotDuplicateSeat(SeatNumber $seatNumber): void
    {
        if ($this->hasSeat($seatNumber)) {
            throw DuplicateSeatException::duplicate(
                $seatNumber->row(),
                $seatNumber->number()
            );
        }
    }

    private function assertSeatRowValid(SeatNumber $seatNumber): void
    {
        if (trim($seatNumber->row()) === '') {
            throw InvalidSeatRow::empty();
        }
    }

    private function assertSeatNumberValid(SeatNumber $seatNumber): void
    {
        if ($seatNumber->number() <= 0) {
            throw InvalidSeatNumber::invalid($seatNumber->number());
        }
    }
}
