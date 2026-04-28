<?php

namespace App\Application\Booking\Services;

use App\Domain\Booking\Aggregates\Booking\Booking;
use App\Domain\Booking\Aggregates\Booking\BookingId;
use App\Domain\Booking\Aggregates\Booking\BookingStatus;
use App\Domain\Booking\Aggregates\Booking\Customer;
use App\Domain\Booking\Repositories\BookingRepository;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Shared\ValueObjects\Money;
use App\Infrastructure\Services\SeatLockManager;
use App\Domain\Scheduling\Repository\ShowtimeRepository;
use App\Domain\Theater\Repositories\SeatRepository;

/**
 * Servicio que orquesta el flujo de creación y gestión de reservas.
 *
 * Coordina la interacción entre los aggregates, repositorios y servicios
 * externos para implementar el caso de uso de reserva de entradas.
 */
class BookingService
{
    public function __construct(
        private BookingRepository $bookingRepository,
        private ShowtimeRepository $showtimeRepository,
        private SeatRepository $seatRepository,
        private SeatLockManager $seatLockManager
    ) {
    }

    /**
     * Crea una nueva reserva inicial (pendiente).
     */
    public function createPendingBooking(
        int $userId,
        ShowtimeId $showtimeId,
        \DateTimeImmutable $expiresAt
    ): Booking {
        $showtime = $this->showtimeRepository->findById($showtimeId);

        if (!$showtime) {
            throw new \DomainException('Showtime not found.');
        }

        if (!$showtime->isActive()) {
            throw new \DomainException('Showtime is not active.');
        }

        if ($showtime->hasStarted()) {
            throw new \DomainException('Cannot book a showtime that has already started.');
        }

        if ($this->bookingRepository->hasActiveBooking($userId, $showtimeId)) {
            throw new \DomainException('User already has an active booking for this showtime.');
        }

        $bookingId = new BookingId(\Ramsey\Uuid\Str::uuid()->toString());

        $booking = Booking::createFromCart(
            $bookingId,
            $userId,
            $showtimeId,
            $expiresAt
        );

        $this->bookingRepository->save($booking);

        return $booking;
    }

    /**
     * Agrega un ticket a una reserva existente.
     */
    public function addTicketToBooking(
        BookingId $bookingId,
        string $seatId,
        string $showtimeId,
        Money $price
    ): Booking {
        $booking = $this->bookingRepository->findById($bookingId);

        if (!$booking) {
            throw new \DomainException('Booking not found.');
        }

        $booking->addTicket(
            \Ramsey\Uuid\Str::uuid()->toString(),
            $seatId,
            $showtimeId,
            $price,
            $price->currency()
        );

        $this->bookingRepository->save($booking);

        return $booking;
    }

    /**
     * Confirma una reserva (y procesa el pago).
     */
    public function confirmBooking(
        BookingId $bookingId,
        \App\Domain\Shared\Enums\PaymentMethod $paymentMethod
    ): Booking {
        $booking = $this->bookingRepository->findById($bookingId);

        if (!$booking) {
            throw new \DomainException('Booking not found.');
        }

        if ($booking->isExpired()) {
            throw new \DomainException('Booking has expired.');
        }

        // In a real implementation, process payment here
        // $paymentResult = $this->paymentGateway->processPayment(...);

        $booking->confirm();
        $this->bookingRepository->save($booking);

        return $booking;
    }

    /**
     * Cancela una reserva.
     */
    public function cancelBooking(
        BookingId $bookingId,
        string $reason = ''
    ): Booking {
        $booking = $this->bookingRepository->findById($bookingId);

        if (!$booking) {
            throw new \DomainException('Booking not found.');
        }

        $booking->cancel($reason);
        $this->bookingRepository->save($booking);

        return $booking;
    }

    /**
     * Verifica la disponibilidad de un asiento para un showtime.
     */
    public function isSeatAvailable(
        \App\Domain\Theater\ValueObjects\SeatNumber $seatNumber,
        \App\Domain\Theater\ValueObjects\AuditoriumId $auditoriumId,
        ShowtimeId $showtimeId
    ): bool {
        return $this->seatRepository->isAvailableForShowtime(
            $seatNumber,
            $auditoriumId,
            $showtimeId
        );
    }

    /**
     * Obtiene una reserva por ID.
     */
    public function getBooking(BookingId $bookingId): ?Booking
    {
        return $this->bookingRepository->findById($bookingId);
    }

    /**
     * Obtiene todas las reservas de un usuario.
     */
    public function getUserBookings(int $userId): array
    {
        return $this->bookingRepository->findByUser($userId);
    }
}
