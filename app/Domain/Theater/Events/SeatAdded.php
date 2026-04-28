<?php

namespace App\Domain\Theater\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Theater\ValueObjects\SeatType;

/**
 * Evento de dominio que se dispara cuando se agrega un asiento a un auditorium.
 *
 * Este evento representa la adición de un nuevo asiento a una sala de cine.
 */
final class SeatAdded extends DomainEvent
{
    public function __construct(
        private AuditoriumId $auditoriumId,
        private SeatNumber $seatNumber,
        private SeatType $seatType,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function auditoriumId(): AuditoriumId
    {
        return $this->auditoriumId;
    }

    public function seatNumber(): SeatNumber
    {
        return $this->seatNumber;
    }

    public function seatType(): SeatType
    {
        return $this->seatType;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'auditorium_id' => $this->auditoriumId->value(),
            'seat_number' => $this->seatNumber->value(),
            'seat_type' => $this->seatType->value,
        ]);
    }
}
