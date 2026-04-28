<?php

namespace App\Domain\Theater\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\ValueObjects\SeatNumber;

/**
 * Evento de dominio que se dispara cuando se elimina un asiento de un auditorium.
 *
 * Este evento representa la eliminación de un asiento de una sala de cine.
 */
final class SeatRemoved extends DomainEvent
{
    public function __construct(
        private AuditoriumId $auditoriumId,
        private SeatNumber $seatNumber,
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

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'auditorium_id' => $this->auditoriumId->value(),
            'seat_number' => $this->seatNumber->value(),
        ]);
    }
}
