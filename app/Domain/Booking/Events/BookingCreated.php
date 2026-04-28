<?php

namespace App\Domain\Booking\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Booking\ValueObjects\BookingId;

/**
 * Evento de dominio que se dispara cuando se crea una nueva reserva.
 */
final class BookingCreated extends DomainEvent
{
    public function __construct(
        private BookingId $bookingId,
        private int $userId,
        private \App\Domain\Scheduling\ValueObjects\ShowtimeId $showtimeId,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function showtimeId(): \App\Domain\Scheduling\ValueObjects\ShowtimeId
    {
        return $this->showtimeId;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'booking_id' => $this->bookingId->value(),
            'user_id' => $this->userId,
            'showtime_id' => $this->showtimeId->value(),
        ]);
    }
}
