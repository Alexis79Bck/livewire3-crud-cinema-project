<?php

namespace App\Domain\Scheduling\Repository;

use App\Domain\Scheduling\Aggregates\Showtime\Showtime;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Theater\ValueObjects\AuditoriumId;

/**
 * Interface del repositorio para el Aggregate Showtime.
 *
 * Define el contrato para la persistencia y recuperación de funciones.
 */
interface ShowtimeRepository
{
    /**
     * Guarda o actualiza un showtime.
     */
    public function save(Showtime $showtime): void;

    /**
     * Busca un showtime por su ID.
     *
     * @return Showtime|null El showtime o null si no existe
     */
    public function findById(ShowtimeId $id): ?Showtime;

    /**
     * Busca showtimes por película.
     *
     * @return Showtime[]
     */
    public function findByMovie(MovieId $movieId): array;

    /**
     * Busca showtimes por auditorio.
     *
     * @return Showtime[]
     */
    public function findByAuditorium(AuditoriumId $auditoriumId): array;

    /**
     * Busca showtimes que se solapen con un horario en un auditorio.
     *
     * @return Showtime[]
     */
    public function findOverlapping(
        AuditoriumId $auditoriumId,
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime,
        ?ShowtimeId $excludeId = null
    ): array;

    /**
     * Elimina un showtime.
     */
    public function delete(Showtime $showtime): void;

    /**
     * Retorna showtimes activos.
     *
     * @return Showtime[]
     */
    public function findActive(): array;

    /**
     * Retorna todos los showtimes.
     *
     * @return Showtime[]
     */
    public function findAll(): array;
}
