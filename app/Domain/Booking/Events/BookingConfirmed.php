<?php

namespace App\Domain\Booking\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Booking\ValueObjects\BookingId;

/**
 * Evento de dominio que se dispara cuando se confirma una reserva.
 */
final class BookingConfirmed extends DomainEvent
{
    public function __construct(
        private BookingId $bookingId,
        private \DateTimeImmutable $confirmedAt,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function confirmedAt(): \DateTimeImmutable
    {
        return $this->confirmedAt;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'booking_id' => $this->bookingId->value(),
            'confirmed_at' => $this->confirmedAt->format('Y-m-d H:i:s'),
        ]);
    }
}
