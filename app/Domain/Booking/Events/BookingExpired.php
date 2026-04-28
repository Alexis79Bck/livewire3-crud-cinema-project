<?php

namespace App\Domain\Booking\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Booking\ValueObjects\BookingId;

/**
 * Evento de dominio que se dispara cuando una reserva expira.
 */
final class BookingExpired extends DomainEvent
{
    public function __construct(
        private BookingId $bookingId,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'booking_id' => $this->bookingId->value(),
        ]);
    }
}
