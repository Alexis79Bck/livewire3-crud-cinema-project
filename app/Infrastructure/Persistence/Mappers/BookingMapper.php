<?php

namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Booking\Aggregates\Booking\Booking;
use App\Domain\Booking\Aggregates\Booking\BookingId;
use App\Domain\Booking\Aggregates\Booking\BookingStatus;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Shared\ValueObjects\Money;
use App\Infrastructure\Persistence\Eloquent\Models\Booking as BookingModel;
use App\Infrastructure\Persistence\Eloquent\Models\Ticket as TicketModel;
use App\Domain\Booking\Aggregates\Booking\TicketEntity;

/**
 * Mapper para convertir entre el aggregate Booking y el modelo Eloquent.
 */
class BookingMapper
{
    public static function toDomain(BookingModel $model): Booking
    {
        $tickets = [];
        // Tickets are loaded eagerly or can be loaded here
        foreach ($model->tickets as $ticketModel) {
            $tickets[] = self::ticketToDomain($ticketModel);
        }

        return Booking::reconstitute(
            new BookingId($model->id),
            $model->user_id,
            new ShowtimeId($model->showtime_id),
            BookingStatus::from($model->booking_status),
            new Money((int)($model->total_amount * 100), $model->currency),
            $model->expires_at,
            $model->confirmed_at,
            $model->cancelled_at,
            $tickets
        );
    }

    public static function toEloquent(Booking $booking): BookingModel
    {
        $model = BookingModel::find($booking->id()->value()) ?? new BookingModel();

        $model->id = $booking->id()->value();
        $model->user_id = $booking->userId();
        $model->showtime_id = $booking->showtimeId()->value();
        $model->booking_status = $booking->status()->value;
        $model->total_amount = $booking->totalAmount()->amountDecimal();
        $model->currency = $booking->totalAmount()->currency();
        $model->expires_at = $booking->expiresAt();
        $model->confirmed_at = $booking->confirmedAt();
        $model->cancelled_at = $booking->cancelledAt();

        return $model;
    }

    public static function ticketToDomain(TicketModel $model): TicketEntity
    {
        $price = new Money((int)($model->price * 100), $model->currency);
        $ticket = new TicketEntity(
            $model->id,
            new BookingId($model->booking_id),
            $model->seat_id,
            $model->showtime_id,
            $price,
            $model->currency,
            $model->ticket_status
        );

        // Reconstitute generated QR code and checksum if they exist
        $reflection = new \ReflectionClass($ticket);
        $qrCodeProperty = $reflection->getProperty('qrCode');
        $qrCodeProperty->setAccessible(true);
        $qrCodeProperty->setValue($ticket, $model->qr_code);

        $checksumProperty = $reflection->getProperty('checksum');
        $checksumProperty->setAccessible(true);
        $checksumProperty->setValue($ticket, $model->checksum);

        return $ticket;
    }

    public static function ticketToEloquent(TicketEntity $ticket, string $bookingId): TicketModel
    {
        $model = TicketModel::find($ticket->id()) ?? new TicketModel();

        $model->id = $ticket->id();
        $model->booking_id = $bookingId;
        $model->seat_id = $ticket->seatId();
        $model->showtime_id = $ticket->showtimeId();
        $model->price = $ticket->price()->amountDecimal();
        $model->currency = $ticket->currency();
        $model->ticket_status = $ticket->status();
        $model->qr_code = $ticket->qrCode();
        $model->checksum = $ticket->checksum();

        return $model;
    }
}
