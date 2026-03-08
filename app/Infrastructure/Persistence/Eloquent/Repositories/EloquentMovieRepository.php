<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Enums\MovieStatus;
use App\Domain\Catalog\Repositories\MovieRepository;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Infrastructure\Persistence\Eloquent\Models\MovieModel;
use App\Infrastructure\Persistence\Mappers\MovieMapper;

class EloquentMovieRepository implements MovieRepository
{
    public function save(Movie $movie): void
    {
        $model = MovieMapper::toEloquent($movie);
        $model->save();
    }

    public function findById(MovieId $id): ?Movie
    {
        $model = MovieModel::find($id->value());

        if (!$model) {
            return null;
        }

        return MovieMapper::toDomain($model);
    }

    public function delete(Movie $movie): void
    {
        MovieModel::where('id', $movie->id()->value())->delete();
    }

    public function listByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $models = MovieModel::whereBetween('release_date', [
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        ])->get();

        return $models
            ->map(fn (MovieModel $model) => MovieMapper::toDomain($model))
            ->toArray();
    }

    public function archive(Movie $movie): void
    {
        MovieModel::where('id', $movie->id()->value())
            ->update(['status' => MovieStatus::ARCHIVED->value]);
    }
}
