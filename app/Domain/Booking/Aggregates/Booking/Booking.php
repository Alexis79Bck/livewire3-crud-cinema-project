<?php

namespace App\Domain\Booking\Aggregates\Booking;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Shared\ValueObjects\Money;
use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Booking\Aggregates\Booking\Exceptions\BookingExpired;
use App\Domain\Booking\Aggregates\Booking\Exceptions\MaxTicketsExceeded;
use App\Domain\Booking\Aggregates\Booking\Exceptions\NotCancellable;
use App\Domain\Booking\ValueObjects\BookingId;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Booking\ValueObjects\BookingStatus;

/**
 * Aggregate Root que representa una reserva (Booking) de entradas de cine.
 *
 * Encapsula toda la información y comportamiento relacionado con una reserva:
 * - Identificador único (BookingId)
 * - Usuario (user_id)
 * - Función (showtime_id)
 * - Estado (pending, confirmed, cancelled, expired, completed)
 * - Monto total
 * - Fecha de expiración
 * - Tickets asociados
 *
 * Reglas de negocio:
 * - Máximo 10 tickets por booking
 * - Expira después de 15 minutos por defecto
 * - No se puede cancelar si ya pasó la ventana de cancelación (2h antes)
 * - Un cliente no puede reservar el mismo asiento dos veces en la misma función
 * - El monto total es la suma de los precios de los tickets
 *
 * Utiliza el patrón de fábrica estática para la creación y el método
 * reconstitute para reconstruir desde datos persistidos.
 */
final class Booking
{
    /** @var DomainEvent[] */
    private array $events = [];

    /** @var TicketEntity[] */
    private array $tickets = [];

    private function __construct(
        private BookingId $id,
        private int $userId,
        private ShowtimeId $showtimeId,
        private BookingStatus $status,
        private Money $totalAmount,
        private \DateTimeImmutable $expiresAt,
        private ?\DateTimeImmutable $confirmedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null,
        ?array $metadata = null
    ) {
    }

    /**
     * Crea una nueva reserva pendiente.
     */
    public static function createFromCart(
        BookingId $id,
        int $userId,
        ShowtimeId $showtimeId,
        \DateTimeImmutable $expiresAt
    ): self {
        $booking = new self(
            $id,
            $userId,
            $showtimeId,
            BookingStatus::PENDING(),
            new Money(0, 'USD'),
            $expiresAt
        );

        $booking->recordEvent(new \App\Domain\Booking\Events\BookingCreated(
            $id,
            $userId,
            $showtimeId
        ));

        return $booking;
    }

    /**
     * Reconstruye un booking desde datos persistidos.
     *
     * @param TicketEntity[] $tickets
     */
    public static function reconstitute(
        BookingId $id,
        int $userId,
        ShowtimeId $showtimeId,
        BookingStatus $status,
        Money $totalAmount,
        \DateTimeImmutable $expiresAt,
        ?\DateTimeImmutable $confirmedAt,
        ?\DateTimeImmutable $cancelledAt,
        array $tickets = []
    ): self {
        $booking = new self(
            $id,
            $userId,
            $showtimeId,
            $status,
            $totalAmount,
            $expiresAt,
            $confirmedAt,
            $cancelledAt
        );
        $booking->tickets = $tickets;
        return $booking;
    }

    /**
     * Agrega un ticket a la reserva.
     *
     * @throws BookingExpired Si la reserva ya expiró
     * @throws MaxTicketsExceeded Si se excede el máximo de tickets
     * @throws \DomainException Si el asiento ya está reservado en este booking
     */
    public function addTicket(
        string $ticketId,
        string $seatId,
        string $showtimeId,
        Money $price,
        string $currency = 'USD'
    ): void {
        $this->assertNotExpired();
        $this->assertNotConfirmed();
        $this->assertNotCancelled();
        $this->assertMaxTicketsNotExceeded();
        $this->assertSeatNotAlreadyBooked($seatId, $showtimeId);

        $this->tickets[] = new TicketEntity(
            $ticketId,
            $this->id,
            $seatId,
            $showtimeId,
            $price,
            $currency
        );

        // Recalcular total
        $this->recalculateTotal();
    }

    /**
     * Confirma la reserva.
     *
     * Transiciona de PENDING a CONFIRMED.
     *
     * @throws BookingExpired Si la reserva ya expiró
     * @throws \DomainException Si la reserva ya está confirmada o cancelada
     */
    public function confirm(): void
    {
        $this->assertNotExpired();
        $this->assertCanBeConfirmed();

        $this->status = BookingStatus::CONFIRMED();
        $this->confirmedAt = new \DateTimeImmutable();

        $this->recordEvent(new \App\Domain\Booking\Events\BookingConfirmed(
            $this->id,
            $this->confirmedAt
        ));

        // Generar QR codes para cada ticket
        $this->generateTicketQRCodes();
    }

    /**
     * Cancela la reserva.
     *
     * @param string $reason Motivo de la cancelación
     * @throws NotCancellable Si la reserva no puede ser cancelada
     * @throws BookingExpired Si la reserva ya expiró
     */
    public function cancel(string $reason = ''): void
    {
        $this->assertCancellable();
        $this->assertNotExpired();

        $this->status = BookingStatus::CANCELLED();
        $this->cancelledAt = new \DateTimeImmutable();

        $this->recordEvent(new \App\Domain\Booking\Events\BookingCancelled(
            $this->id,
            $reason,
            $this->cancelledAt
        ));
    }

    /**
     * Marca la reserva como expirada.
     */
    public function expire(): void
    {
        if ($this->status->isConfirmed()) {
            return; // No se expira si ya está confirmada
        }

        $this->status = BookingStatus::EXPIRED();

        $this->recordEvent(new \App\Domain\Booking\Events\BookingExpired(
            $this->id
        ));
    }

    /**
     * Verifica si la reserva ha expirado.
     */
    public function isExpired(): bool
    {
        if ($this->status->isConfirmed()) {
            return false;
        }

        $now = new \DateTimeImmutable();
        return $now > $this->expiresAt;
    }

    /**
     * Verifica si la reserva es cancelable.
     */
    public function isCancellable(): bool
    {
        if (!$this->status->isPending() && !$this->status->isConfirmed()) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        // Si está confirmada, verificar ventana de cancelación
        if ($this->status->isConfirmed()) {
            return $this->isWithinCancellationWindow();
        }

        return true;
    }

    /**
     * Verifica si la reserva está dentro de la ventana de cancelación.
     * Por defecto, se puede cancelar hasta 2 horas antes del showtime.
     */
    public function isWithinCancellationWindow(\DateTimeImmutable $showtimeStart = null): bool
    {
        if (!$this->status->isConfirmed()) {
            return true;
        }

        if ($showtimeStart === null) {
            return true;
        }

        $now = new \DateTimeImmutable();
        $cancellationDeadline = $showtimeStart->sub(new \DateInterval('PT2H'));

        return $now < $cancellationDeadline;
    }

    /**
     * Calcula el monto total de la reserva.
     */
    public function calculateTotal(): Money
    {
        $total = new Money(0, $this->totalAmount->currency());

        foreach ($this->tickets as $ticket) {
            $total = $total->add($ticket->price());
        }

        return $total;
    }

    /**
     * Verifica si el asiento ya está reservado en este booking.
     */
    public function hasSeat(string $seatId, string $showtimeId): bool
    {
        foreach ($this->tickets as $ticket) {
            if ($ticket->seatId() === $seatId && $ticket->showtimeId() === $showtimeId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtiene todos los tickets de la reserva.
     *
     * @return TicketEntity[]
     */
    public function tickets(): array
    {
        return $this->tickets;
    }

    /**
     * Verifica si la reserva está confirmada.
     */
    public function isConfirmed(): bool
    {
        return $this->status->isConfirmed();
    }

    /**
     * Verifica si la reserva está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status->isPending();
    }

    /**
     * Verifica si la reserva está en estado finalizado.
     */
    public function isFinalized(): bool
    {
        return $this->status->isFinalized();
    }

    /**
     * Retorna el total de tickets.
     */
    public function ticketCount(): int
    {
        return count($this->tickets);
    }

    /**
     * Retorna el ID de la reserva.
     */
    public function id(): BookingId
    {
        return $this->id;
    }

    /**
     * Retorna el ID del usuario.
     */
    public function userId(): int
    {
        return $this->userId;
    }

    /**
     * Retorna el ID de la función.
     */
    public function showtimeId(): ShowtimeId
    {
        return $this->showtimeId;
    }

    /**
     * Retorna el estado de la reserva.
     */
    public function status(): BookingStatus
    {
        return $this->status;
    }

    /**
     * Retorna el monto total.
     */
    public function totalAmount(): Money
    {
        return $this->totalAmount;
    }

    /**
     * Retorna la fecha de expiración.
     */
    public function expiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * Retorna la fecha de confirmación.
     */
    public function confirmedAt(): ?\DateTimeImmutable
    {
        return $this->confirmedAt;
    }

    /**
     * Retorna la fecha de cancelación.
     */
    public function cancelledAt(): ?\DateTimeImmutable
    {
        return $this->cancelledAt;
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

    private function assertNotExpired(): void
    {
        if ($this->status === BookingStatus::EXPIRED() || $this->isExpired()) {
            throw BookingExpired::expired($this->id);
        }
    }

    private function assertNotConfirmed(): void
    {
        if ($this->status->isConfirmed()) {
            throw new \DomainException('Booking is already confirmed.');
        }
    }

    private function assertNotCancelled(): void
    {
        if ($this->status->isFinalized()) {
            throw new \DomainException('Booking is already finalized.');
        }
    }

    private function assertMaxTicketsNotExceeded(): void
    {
        if (count($this->tickets) >= 10) {
            throw MaxTicketsExceeded::limit(10);
        }
    }

    private function assertSeatNotAlreadyBooked(string $seatId, string $showtimeId): void
    {
        if ($this->hasSeat($seatId, $showtimeId)) {
            throw new \DomainException(sprintf(
                'Seat %s is already booked in this booking for showtime %s.',
                $seatId,
                $showtimeId
            ));
        }
    }

    private function assertCanBeConfirmed(): void
    {
        if ($this->status->isFinalized()) {
            throw new \DomainException('Cannot confirm a finalized booking.');
        }

        if (empty($this->tickets)) {
            throw new \DomainException('Cannot confirm a booking with no tickets.');
        }
    }

    private function assertCancellable(): void
    {
        if (!$this->isCancellable()) {
            throw NotCancellable::forBooking($this->id);
        }
    }

    private function recalculateTotal(): void
    {
        $this->totalAmount = $this->calculateTotal();
    }

    private function generateTicketQRCodes(): void
    {
        foreach ($this->tickets as $ticket) {
            $ticket->generateQRCode();
        }
    }
}
