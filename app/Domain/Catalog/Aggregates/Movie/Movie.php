<?php

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
    private MovieId $id;

    private Title $title;

    private Plot $plot;

    private ReleaseDate $releaseDate;

    private Rating $rating;

    private Image $image;

    private MovieStatus $status;

    private function __construct(
        MovieId $id,
        Title $title,
        Plot $plot,
        ReleaseDate $releaseDate,
        Rating $rating,
        Image $image,
        MovieStatus $status
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->plot = $plot;
        $this->releaseDate = $releaseDate;
        $this->rating = $rating;
        $this->image = $image;
        $this->status = $status;
    }

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
}
