<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Theater\Repositories\SeatRepository;
use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Theater\ValueObjects\SeatType;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Infrastructure\Persistence\Eloquent\Models\Seat as SeatModel;
use App\Infrastructure\Persistence\Eloquent\Models\Ticket as TicketModel;

class EloquentSeatRepository implements SeatRepository
{
    public function isAvailableForShowtime(
        SeatNumber $seatNumber,
        AuditoriumId $auditoriumId,
        ShowtimeId $showtimeId
    ): bool {
        // First check if seat exists in auditorium
        $seat = SeatModel::where('auditorium_id', $auditoriumId->value())
            ->where('row', $seatNumber->row())
            ->where('number', $seatNumber->number())
            ->first();

        if (!$seat) {
            return false;
        }

        // Check if seat is already booked for this showtime
        // with an active or confirmed booking
        $isBooked = TicketModel::where('seat_id', $seat->id)
            ->where('showtime_id', $showtimeId->value())
            ->whereIn('ticket_status', ['ACTIVE', 'USED', 'REFUNDED'])
            ->whereHas('booking', function ($query) {
                $query->whereIn('booking_status', ['pending', 'confirmed']);
            })
            ->exists();

        return !$isBooked;
    }

    public function findByAuditorium(AuditoriumId $auditoriumId): array
    {
        $seats = SeatModel::where('auditorium_id', $auditoriumId->value())
            ->get();

        return $seats->map(function (SeatModel $seat) {
            return [
                'id' => $seat->id,
                'row' => $seat->row,
                'number' => $seat->number,
                'type' => $seat->type,
                'position_x' => $seat->position_x,
                'position_y' => $seat->position_y,
            ];
        })->toArray();
    }

    public function countByType(
        AuditoriumId $auditoriumId,
        SeatType $seatType
    ): int {
        return SeatModel::where('auditorium_id', $auditoriumId->value())
            ->where('type', $seatType->value)
            ->count();
    }

    public function existsInAuditorium(
        SeatNumber $seatNumber,
        AuditoriumId $auditoriumId
    ): bool {
        return SeatModel::where('auditorium_id', $auditoriumId->value())
            ->where('row', $seatNumber->row())
            ->where('number', $seatNumber->number())
            ->exists();
    }

    public function isReservedForShowtime(
        SeatNumber $seatNumber,
        AuditoriumId $auditoriumId,
        ShowtimeId $showtimeId
    ): bool {
        return !$this->isAvailableForShowtime($seatNumber, $auditoriumId, $showtimeId);
    }
}
