<?php

/**
 * Data Transfer Object para transferir datos de películas entre capas.
 *
 * Este DTO se utiliza para transferir información de películas desde la capa
 * de aplicación hacia la capa de presentación, evitando exponer directamente
 * los objetos de dominio. Proporciona una representación serializable y
 * segura de los datos de una película.
 *
 * El DTO contiene:
 * - Identificador único de la película
 * - Título de la película
 * - Sinopsis/Trama
 * - Fecha de estreno
 * - Clasificación de edad
 * - URL de la imagen/póster
 * - Estado actual de la película
 * - Fecha de creación
 * - Fecha de última actualización
 *
 * Este patrón permite:
 * - Desacoplar la capa de presentación del dominio
 * - Controlar qué información se expone
 * - Facilitar la serialización a JSON/XML
 * - Mejorar el rendimiento al evitar cargar relaciones innecesarias
 *
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie Aggregate de origen
 * @see \App\Application\Catalog\Movie\Handlers\GetMovieByIdHandler Handler que utiliza este DTO
 */

namespace App\Application\Catalog\Movie\DTOs;

use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Enums\MovieStatus;

final class MovieDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $plot,
        public readonly string $releaseDate,
        public readonly string $rating,
        public readonly string $image,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly ?string $updatedAt = null
    ) {}

    /**
     * Crea un DTO desde un objeto Movie del dominio.
     */
    public static function fromDomain(Movie $movie): self
    {
        return new self(
            id: $movie->id()->value(),
            title: $movie->title()->value(),
            plot: $movie->plot()->value(),
            releaseDate: $movie->releaseDate()->value()->format('Y-m-d'),
            rating: $movie->rating()->value(),
            image: $movie->image()->value(),
            status: $movie->status()->value,
            createdAt: $movie->releaseDate()->value()->format('Y-m-d H:i:s'),
            updatedAt: null
        );
    }

    /**
     * Crea un DTO desde un array de datos.
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            plot: $data['plot'],
            releaseDate: $data['release_date'],
            rating: $data['rating'],
            image: $data['image'],
            status: $data['status'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'] ?? null
        );
    }

    /**
     * Convierte el DTO a un array para serialización.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'plot' => $this->plot,
            'release_date' => $this->releaseDate,
            'rating' => $this->rating,
            'image' => $this->image,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    /**
     * Convierte el DTO a JSON.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
