<?php

/**
 * Mapper para convertir entre objetos de dominio Movie y modelos Eloquent.
 *
 * Esta clase proporciona métodos estáticos para transformar objetos de dominio
 * Movie (capa de dominio) a modelos Eloquent MovieModel (capa de infraestructura)
 * y viceversa. Maneja la traducción de datos entre las dos capas manteniendo
 * la separación limpia del dominio.
 *
 * El patrón Mapper es parte de la arquitectura de infraestructura y permite
 * que el dominio permanezca libre de dependencias de frameworks externos.
 * Utiliza los Value Objects del dominio para crear instancias de Movie.
 *
 * @see Movie Modelo de dominio
 * @see MovieModel Modelo Eloquent
 */

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
