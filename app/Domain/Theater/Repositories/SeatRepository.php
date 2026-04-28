<?php

namespace App\Domain\Theater\Repositories;

use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Theater\ValueObjects\SeatType;

/**
 * Interface del repositorio para la gestión de asientos.
 *
 * Permite consultar la disponibilidad y estado de los asientos.
 */
interface SeatRepository
{
    /**
     * Verifica si un asiento está disponible para una función.
     */
    public function isAvailableForShowtime(
        SeatNumber $seatNumber,
        AuditoriumId $auditoriumId,
        \App\Domain\Scheduling\ValueObjects\ShowtimeId $showtimeId
    ): bool;

    /**
     * Obtiene los asientos de un auditorio.
     *
     * @return array Array de asientos
     */
    public function findByAuditorium(AuditoriumId $auditoriumId): array;

    /**
     * Cuenta los asientos por tipo en un auditorio.
     */
    public function countByType(
        AuditoriumId $auditoriumId,
        SeatType $seatType
    ): int;

    /**
     * Verifica si un asiento existe en el auditorio.
     */
    public function existsInAuditorium(
        SeatNumber $seatNumber,
        AuditoriumId $auditoriumId
    ): bool;

    /**
     * Verifica si un asiento está reservado/ocupado en un showtime.
     */
    public function isReservedForShowtime(
        SeatNumber $seatNumber,
        AuditoriumId $auditoriumId,
        \App\Domain\Scheduling\ValueObjects\ShowtimeId $showtimeId
    ): bool;
}
