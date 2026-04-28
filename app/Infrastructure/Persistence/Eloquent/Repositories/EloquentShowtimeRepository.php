<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Scheduling\Repository\ShowtimeRepository;
use App\Domain\Scheduling\Aggregates\Showtime\Showtime;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Shared\ValueObjects\DateRange;
use App\Infrastructure\Persistence\Eloquent\Models\Showtime as ShowtimeModel;
use App\Infrastructure\Persistence\Mappers\ShowtimeMapper;
use Illuminate\Support\Facades\DB;

class EloquentShowtimeRepository implements ShowtimeRepository
{
    public function save(Showtime $showtime): void
    {
        $model = ShowtimeMapper::toEloquent($showtime);
        $model->save();
    }

    public function findById(ShowtimeId $id): ?Showtime
    {
        $model = ShowtimeModel::find($id->value());

        if (!$model) {
            return null;
        }

        return ShowtimeMapper::toDomain($model);
    }

    public function findByMovie(MovieId $movieId): array
    {
        $models = ShowtimeModel::where('movie_id', $movieId->value())
            ->where('is_active', true)
            ->get();

        return $models
            ->map(fn (ShowtimeModel $model) => ShowtimeMapper::toDomain($model))
            ->toArray();
    }

    public function findByAuditorium(AuditoriumId $auditoriumId): array
    {
        $models = ShowtimeModel::where('auditorium_id', $auditoriumId->value())
            ->where('is_active', true)
            ->get();

        return $models
            ->map(fn (ShowtimeModel $model) => ShowtimeMapper::toDomain($model))
            ->toArray();
    }

    public function findOverlapping(
        AuditoriumId $auditoriumId,
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime,
        ?ShowtimeId $excludeId = null
    ): array {
        $query = ShowtimeModel::where('auditorium_id', $auditoriumId->value())
            ->where('is_active', true)
            ->where('start_time', '<', $endTime->format('Y-m-d H:i:s'))
            ->where('end_time', '>', $startTime->format('Y-m-d H:i:s'));

        if ($excludeId) {
            $query->where('id', '!=', $excludeId->value());
        }

        $models = $query->get();

        return $models
            ->map(fn (ShowtimeModel $model) => ShowtimeMapper::toDomain($model))
            ->toArray();
    }

    public function delete(Showtime $showtime): void
    {
        ShowtimeModel::where('id', $showtime->id()->value())->delete();
    }

    public function findActive(): array
    {
        $models = ShowtimeModel::where('is_active', true)
            ->where('end_time', '>', now())
            ->get();

        return $models
            ->map(fn (ShowtimeModel $model) => ShowtimeMapper::toDomain($model))
            ->toArray();
    }

    public function findAll(): array
    {
        $models = ShowtimeModel::all();

        return $models
            ->map(fn (ShowtimeModel $model) => ShowtimeMapper::toDomain($model))
            ->toArray();
    }
}
