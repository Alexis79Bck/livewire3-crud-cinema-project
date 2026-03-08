<?php

namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Enums\MovieStatus;
use App\Domain\Catalog\ValueObjects\Image;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Plot;
use App\Domain\Catalog\ValueObjects\Rating;
use App\Domain\Catalog\ValueObjects\ReleaseDate;
use App\Domain\Catalog\ValueObjects\Title;
use App\Infrastructure\Persistence\Eloquent\Models\MovieModel;

class MovieMapper
{
    public static function toDomain(MovieModel $model): Movie
    {
        return Movie::reconstitute(
            new MovieId($model->id),
            new Title($model->title),
            new Plot($model->plot),
            new ReleaseDate($model->release_date),
            new Rating($model->rating),
            new Image($model->image),
            MovieStatus::from($model->status)
        );
    }

    public static function toEloquent(Movie $movie): MovieModel
    {
        $model = MovieModel::find($movie->id()->value()) ?? new MovieModel();

        $model->id = $movie->id()->value();
        $model->title = $movie->title()->value();
        $model->plot = $movie->plot()->value();
        $model->release_date = $movie->releaseDate()->value();
        $model->rating = $movie->rating()->value();
        $model->image = $movie->image()->value();
        $model->status = $movie->status()->value;

        return $model;
    }
}
