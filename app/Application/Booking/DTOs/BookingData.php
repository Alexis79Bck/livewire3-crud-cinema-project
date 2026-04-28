<?php

namespace App\Application\Booking\DTOs;

/**
 * DTO que representa los datos de una reserva para respuestas API.
 */
class BookingData
{
    public function __construct(
        public string $id,
        public int $userId,
        public string $showtimeId,
        public string $status,
        public float $totalAmount,
        public string $currency,
        public string $expiresAt,
        public ?string $confirmedAt = null,
        public ?string $cancelledAt = null,
        public array $tickets = [],
        public ?array $metadata = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'showtimeId' => $this->showtimeId,
            'status' => $this->status,
            'totalAmount' => $this->totalAmount,
            'currency' => $this->currency,
            'expiresAt' => $this->expiresAt,
            'confirmedAt' => $this->confirmedAt,
            'cancelledAt' => $this->cancelledAt,
            'tickets' => $this->tickets,
            'metadata' => $this->metadata,
        ];
    }
}
