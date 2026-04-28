<?php

namespace App\Domain\Booking\Aggregates\Booking;

use App\Domain\Shared\ValueObjects\Money;

/**
 * Entidad que representa un ticket (entrada) dentro de una reserva.
 */
final class TicketEntity
{
    private ?string $qrCode = null;
    private ?string $checksum = null;

    public function __construct(
        private string $id,
        private \App\Domain\Booking\ValueObjects\BookingId $bookingId,
        private string $seatId,
        private string $showtimeId,
        private Money $price,
        private string $currency = 'USD',
        private string $status = 'ACTIVE'
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function bookingId(): \App\Domain\Booking\ValueObjects\BookingId
    {
        return $this->bookingId;
    }

    public function seatId(): string
    {
        return $this->seatId;
    }

    public function showtimeId(): string
    {
        return $this->showtimeId;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function qrCode(): ?string
    {
        return $this->qrCode;
    }

    public function checksum(): ?string
    {
        return $this->checksum;
    }

    public function generateQRCode(): void
    {
        $data = json_encode([
            'ticket_id' => $this->id,
            'booking_id' => $this->bookingId->value(),
            'seat_id' => $this->seatId,
            'showtime_id' => $this->showtimeId,
            'price' => $this->price->amount(),
            'currency' => $this->currency,
        ]);
        $this->qrCode = base64_encode($data);
        $this->checksum = hash('sha256', $data);
    }

    public function markAsUsed(): void
    {
        $this->status = 'USED';
    }

    public function markAsCancelled(): void
    {
        $this->status = 'CANCELLED';
    }

    public function markAsRefunded(): void
    {
        $this->status = 'REFUNDED';
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }
}
