<?php

namespace App\Domain\Booking\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Booking\ValueObjects\BookingId;

/**
 * Evento de dominio que se dispara cuando se cancela una reserva.
 */
final class BookingCancelled extends DomainEvent
{
    public function __construct(
        private BookingId $bookingId,
        private string $reason,
        private \DateTimeImmutable $cancelledAt,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function cancelledAt(): \DateTimeImmutable
    {
        return $this->cancelledAt;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'booking_id' => $this->bookingId->value(),
            'reason' => $this->reason,
            'cancelled_at' => $this->cancelledAt->format('Y-m-d H:i:s'),
        ]);
    }
}
