<?php

namespace App\Domain\Booking\Repositories;

use App\Domain\Booking\Aggregates\Booking\Booking;
use App\Domain\Booking\ValueObjects\BookingId;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;

/**
 * Interface del repositorio para el Aggregate Booking.
 *
 * Define el contrato para la persistencia y recuperación de reservas.
 * Esta interfaz sigue el patrón Repository de DDD, aislando la lógica
 * de dominio de los detalles de implementación de persistencia.
 */
interface BookingRepository
{
    /**
     * Guarda o actualiza una reserva en el repositorio.
     */
    public function save(Booking $booking): void;

    /**
     * Busca una reserva por su identificador.
     *
     * @return Booking|null La reserva encontrada o null si no existe
     */
    public function findById(BookingId $id): ?Booking;

    /**
     * Busca reservas por usuario.
     *
     * @return Booking[] Array de reservas del usuario
     */
    public function findByUser(int $userId): array;

    /**
     * Busca reservas por función.
     *
     * @return Booking[] Array de reservas para el showtime
     */
    public function findByShowtime(ShowtimeId $showtimeId): array;

    /**
     * Elimina una reserva del repositorio.
     */
    public function delete(Booking $booking): void;

    /**
     * Busca reservas por estado.
     *
     * @return Booking[] Array de reservas con el estado especificado
     */
    public function findByStatus(string $status): array;

    /**
     * Retorna todas las reservas.
     *
     * @return Booking[] Array de reservas
     */
    public function findAll(): array;

    /**
     * Verifica si un usuario ya tiene una reserva activa para un showtime.
     */
    public function hasActiveBooking(int $userId, ShowtimeId $showtimeId): bool;
}
