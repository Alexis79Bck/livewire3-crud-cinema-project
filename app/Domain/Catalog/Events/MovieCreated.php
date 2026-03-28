<?php

/**
 * Evento de dominio que se dispara cuando se crea una nueva película.
 *
 * Este evento representa la creación de una nueva película en el catálogo del cine.
 * Se publica cuando se invoca el método `create()` del aggregate Movie.
 *
 * El evento contiene información relevante sobre la película creadada:
 * - Identificador único de la película
 * - Título de la película
 * - Estado inicial (siempre DRAFT)
 * - Fecha de creación
 *
 * Este evento puede ser utilizado para:
 * - Notificar a otros bounded contexts sobre la nueva película
 * - Registrar auditoría de creación de películas
 * - Sincronizar catálogos externos
 * - Generar reportes de actividad
 *
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie::create() Método que publica este evento
 * @see \App\Domain\Shared\Events\DomainEvent Clase base del evento
 */

namespace App\Domain\Catalog\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Title;
use App\Domain\Catalog\Enums\MovieStatus;

final class MovieCreated extends DomainEvent
{
    public function __construct(
        private MovieId $movieId,
        private Title $title,
        private MovieStatus $status,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    /**
     * Retorna el identificador de la película creada.
     */
    public function movieId(): MovieId
    {
        return $this->movieId;
    }

    /**
     * Retorna el título de la película creada.
     */
    public function title(): Title
    {
        return $this->title;
    }

    /**
     * Retorna el estado inicial de la película (siempre DRAFT).
     */
    public function status(): MovieStatus
    {
        return $this->status;
    }

    /**
     * Convierte el evento a un array para serialización.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'movie_id' => $this->movieId->value(),
            'title' => $this->title->value(),
            'status' => $this->status->value,
        ]);
    }
}
