<?php

namespace App\Domain\Theater\Aggregates\Auditorium;

use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Theater\ValueObjects\SeatType;

/**
 * Entidad que representa un asiento dentro de un auditorium.
 *
 * Es una entidad porque su identidad se basa en su ubicación (row + number)
 * dentro de un auditorium específico, no en un ID propio.
 */
final class SeatEntity
{
    public function __construct(
        private SeatNumber $seatNumber,
        private SeatType $seatType
    ) {
    }

    public function seatNumber(): SeatNumber
    {
        return $this->seatNumber;
    }

    public function seatType(): SeatType
    {
        return $this->seatType;
    }
}
