<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Booking\Repositories\BookingRepository;
use App\Domain\Booking\Aggregates\Booking\Booking;
use App\Domain\Booking\ValueObjects\BookingId;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Infrastructure\Persistence\Eloquent\Models\Booking as BookingModel;
use App\Infrastructure\Persistence\Mappers\BookingMapper;

class EloquentBookingRepository implements BookingRepository
{
    public function save(Booking $booking): void
    {
        $model = BookingMapper::toEloquent($booking);
        $model->save();

        // Sync tickets
        $this->syncTickets($booking, $model);
    }

    public function findById(BookingId $id): ?Booking
    {
        $model = BookingModel::with(['tickets'])->find($id->value());

        if (!$model) {
            return null;
        }

        return BookingMapper::toDomain($model);
    }

    public function findByUser(int $userId): array
    {
        $models = BookingModel::with(['tickets'])
            ->where('user_id', $userId)
            ->get();

        return $models
            ->map(fn (BookingModel $model) => BookingMapper::toDomain($model))
            ->toArray();
    }

    public function findByShowtime(ShowtimeId $showtimeId): array
    {
        $models = BookingModel::with(['tickets'])
            ->where('showtime_id', $showtimeId->value())
            ->get();

        return $models
            ->map(fn (BookingModel $model) => BookingMapper::toDomain($model))
            ->toArray();
    }

    public function delete(Booking $booking): void
    {
        // Delete tickets first due to foreign key constraint
        $model = BookingModel::find($booking->id()->value());
        if ($model) {
            $model->tickets()->delete();
            $model->delete();
        }
    }

    public function findByStatus(string $status): array
    {
        $models = BookingModel::with(['tickets'])
            ->where('booking_status', $status)
            ->get();

        return $models
            ->map(fn (BookingModel $model) => BookingMapper::toDomain($model))
            ->toArray();
    }

    public function findAll(): array
    {
        $models = BookingModel::with(['tickets'])->get();

        return $models
            ->map(fn (BookingModel $model) => BookingMapper::toDomain($model))
            ->toArray();
    }

    public function hasActiveBooking(int $userId, ShowtimeId $showtimeId): bool
    {
        return BookingModel::where('user_id', $userId)
            ->where('showtime_id', $showtimeId->value())
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->exists();
    }

    private function syncTickets(Booking $booking, BookingModel $model): void
    {
        // Delete existing tickets
        $model->tickets()->delete();

        // Save new tickets
        foreach ($booking->tickets() as $ticket) {
            $ticketModel = \App\Infrastructure\Persistence\Mappers\BookingMapper::ticketToEloquent(
                $ticket,
                $model->id
            );
            $model->tickets()->save($ticketModel);
        }
    }
}
