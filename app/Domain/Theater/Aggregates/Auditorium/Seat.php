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

<<<<<<< HEAD
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
=======
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
>>>>>>> develop
    }

    public function seatNumber(): SeatNumber
    {
        return $this->seatNumber;
    }

<<<<<<< HEAD
    public function seatType(): SeatType
    {
        return $this->seatType;
=======
    public function type(): SeatType
    {
        return $this->type;
    }

    public function equals(self $other): bool
    {
        return $this->seatNumber->equals($other->seatNumber) && 
               $this->type === $other->type;
>>>>>>> develop
    }
}
