<?php

namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Scheduling\Aggregates\Showtime\Showtime;
use App\Domain\Scheduling\ValueObjects\Schedule;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Scheduling\ValueObjects\ShowtimeStatus;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Shared\ValueObjects\Money;
use App\Infrastructure\Persistence\Eloquent\Models\Showtime as ShowtimeModel;

/**
 * Mapper para convertir entre el aggregate Showtime y el modelo Eloquent.
 */
class ShowtimeMapper
{
    public static function toDomain(ShowtimeModel $model): Showtime
    {
        $schedule = new Schedule(
            $model->start_time,
            $model->end_time
        );

        return Showtime::reconstitute(
            new ShowtimeId($model->id),
            new MovieId($model->movie_id),
            new AuditoriumId($model->auditorium_id),
            $schedule,
            new Money((int)($model->base_price * 100), $model->currency ?? 'USD'),
            ShowtimeStatus::from($model->is_active ? 'active' : 'inactive')
        );
    }

    public static function toEloquent(Showtime $showtime): ShowtimeModel
    {
        $model = ShowtimeModel::find($showtime->id()->value()) ?? new ShowtimeModel();

        $model->id = $showtime->id()->value();
        $model->movie_id = $showtime->movieId()->value();
        $model->auditorium_id = $showtime->auditoriumId()->value();
        $model->start_time = $showtime->startTime();
        $model->end_time = $showtime->endTime();
        $model->base_price = $showtime->basePrice()->amountDecimal();
        $model->is_active = $showtime->isActive();

        return $model;
    }
}
