<?php

/**
 * Value Object que representa un asiento en un auditorio.
 *
 * Este objeto valor encapsula toda la información relevante de un asiento,
 * incluyendo su número(identificador) y tipo.
 *
 * El asiento es inmutable una vez creado.
 */

namespace App\Domain\Theater\Aggregates\Auditorium;

use App\Domain\Shared\Enums\SeatType;

final class Seat
{
    public function __construct(
        private readonly SeatNumber $seatNumber,
        private readonly SeatType $type
    ) {}

    public static function create(SeatNumber $seatNumber, SeatType $type): self
    {
        return new self($seatNumber, $type);
    }

    public function seatNumber(): SeatNumber
    {
        return $this->seatNumber;
    }

    public function type(): SeatType
    {
        return $this->type;
    }

    public function equals(self $other): bool
    {
        return $this->seatNumber->equals($other->seatNumber) && 
               $this->type === $other->type;
    }
}
