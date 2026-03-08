<?php

/**
 * Aggregate Root que representa una película en el catálogo del cine.
 *
 * Esta clase encapsula toda la información y comportamiento relacionado con una película,
 * incluyendo:
 * - Identificador único (MovieId)
 * - Título (Title)
 * - Sinopsis/Trama (Plot)
 * - Fecha de estreno (ReleaseDate)
 * - Clasificación de edad (Rating)
 * - Imagen/Póster (Image)
 * - Estado de publicación (MovieStatus)
 *
 * El aggregate garantiza la consistencia de los datos y encapsula las reglas de negocio
 * relacionadas con el ciclo de vida de una película (creación, publicación, archivado).
 *
 * Utiliza el patrón de fábrica estático (factory method) para la creación de instancias
 * y el método reconstitute para reconstruir el aggregate desde una fuente de datos persistida.
 *
 * @see MovieStatus Estados posibles de la película
 * @see InvalidMovieStatus Excepción lanzada cuando el cambio de estado es inválido
 */


namespace App\Domain\Catalog\Aggregates\Movie;

use App\Domain\Catalog\Enums\MovieStatus;
use App\Domain\Catalog\Exceptions\InvalidMovieStatus;
use App\Domain\Catalog\ValueObjects\Image;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Title;
use App\Domain\Catalog\ValueObjects\Plot;
use App\Domain\Catalog\ValueObjects\ReleaseDate;
use App\Domain\Catalog\ValueObjects\Rating;


final class Movie
{
    private function __construct(
        private MovieId $id,
        private Title $title,
        private Plot $plot,
        private ReleaseDate $releaseDate,
        private Rating $rating,
        private Image $image,
        private MovieStatus $status
    ) {}

    public static function create(
        MovieId $id,
        Title $title,
        Plot $plot,
        ReleaseDate $releaseDate,
        Rating $rating,
        Image $image
    ): self {
        return new self(
            $id,
            $title,
            $plot,
            $releaseDate,
            $rating,
            $image,
            MovieStatus::DRAFT
        );
    }

    public static function reconstitute(
        MovieId $id,
        Title $title,
        Plot $plot,
        ReleaseDate $releaseDate,
        Rating $rating,
        Image $image,
        MovieStatus $status
    ): self {
        return new self(
            $id,
            $title,
            $plot,
            $releaseDate,
            $rating,
            $image,
            $status
        );
    }

    public function publish(): void
    {
        if ($this->status === MovieStatus::PUBLISHED) {
            throw InvalidMovieStatus::published();
        }

        $this->status = MovieStatus::PUBLISHED;
    }

    public function archive(): void
    {
        $this->status = MovieStatus::ARCHIVED;
    }

    public function id(): MovieId
    {
        return $this->id;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function plot(): Plot
    {
        return $this->plot;
    }

    public function releaseDate(): ReleaseDate
    {
        return $this->releaseDate;
    }

    public function rating(): Rating
    {
        return $this->rating;
    }

    public function image(): Image
    {
        return $this->image;
    }

    public function status(): MovieStatus
    {
        return $this->status;
    }
}
