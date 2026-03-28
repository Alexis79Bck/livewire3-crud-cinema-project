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
use App\Domain\Catalog\Events\MovieCreated;
use App\Domain\Catalog\Events\MoviePublished;
use App\Domain\Catalog\Events\MovieArchived;
use App\Domain\Catalog\Exceptions\InvalidMovieStatus;
use App\Domain\Catalog\ValueObjects\Image;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Title;
use App\Domain\Catalog\ValueObjects\Plot;
use App\Domain\Catalog\ValueObjects\ReleaseDate;
use App\Domain\Catalog\ValueObjects\Rating;
use App\Domain\Shared\Events\DomainEvent;


final class Movie
{
    /**
     * @var DomainEvent[]
     */
    private array $events = [];

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
        $movie = new self(
            $id,
            $title,
            $plot,
            $releaseDate,
            $rating,
            $image,
            MovieStatus::DRAFT
        );

        $movie->recordEvent(new MovieCreated(
            $id,
            $title,
            MovieStatus::DRAFT
        ));

        return $movie;
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

        if ($this->status === MovieStatus::ARCHIVED) {
            throw InvalidMovieStatus::archived();
        }

        $previousStatus = $this->status;
        $this->status = MovieStatus::PUBLISHED;

        $this->recordEvent(new MoviePublished(
            $this->id,
            $this->title,
            $previousStatus,
            $this->status
        ));
    }

    public function archive(): void
    {
        $previousStatus = $this->status;
        $this->status = MovieStatus::ARCHIVED;

        $this->recordEvent(new MovieArchived(
            $this->id,
            $this->title,
            $previousStatus,
            $this->status
        ));
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

    /**
     * Verifica si la película puede transicionar a un nuevo estado.
     *
     * @param MovieStatus $newStatus Estado al que se quiere transicionar
     * @return bool True si la transición es válida, false en caso contrario
     */
    public function canTransitionTo(MovieStatus $newStatus): bool
    {
        // No se puede transicionar al mismo estado
        if ($this->status === $newStatus) {
            return false;
        }

        // Una película archivada no puede cambiar de estado
        if ($this->status === MovieStatus::ARCHIVED) {
            return false;
        }

        // Una película publicada solo puede archivarse
        if ($this->status === MovieStatus::PUBLISHED && $newStatus !== MovieStatus::ARCHIVED) {
            return false;
        }

        return true;
    }

    /**
     * Registra un evento de dominio para ser publicado posteriormente.
     */
    protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Retorna todos los eventos registrados y los limpia.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    /**
     * Retorna todos los eventos registrados sin limpiarlos.
     *
     * @return DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Limpia todos los eventos registrados.
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }
}
