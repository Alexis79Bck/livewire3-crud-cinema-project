<?php

/**
 * Evento de dominio que se dispara cuando se publica una película.
 *
 * Este evento representa la publicación de una película en el catálogo del cine.
 * Se publica cuando se invoca el método `publish()` del aggregate Movie.
 *
 * El evento contiene información relevante sobre la publicación:
 * - Identificador único de la película
 * - Título de la película
 * - Estado anterior (DRAFT)
 * - Nuevo estado (PUBLISHED)
 * - Fecha de publicación
 *
 * Este evento puede ser utilizado para:
 * - Notificar a otros bounded contexts que la película está disponible
 * - Actualizar catálogos externos
 * - Sincronizar sistemas de reservas
 * - Generar notificaciones a usuarios interesados
 * - Registrar auditoría de cambios de estado
 *
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie::publish() Método que publica este evento
 * @see \App\Domain\Shared\Events\DomainEvent Clase base del evento
 */

namespace App\Domain\Catalog\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Title;
use App\Domain\Catalog\Enums\MovieStatus;

final class MoviePublished extends DomainEvent
{
    public function __construct(
        private MovieId $movieId,
        private Title $title,
        private MovieStatus $previousStatus,
        private MovieStatus $newStatus,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    /**
     * Retorna el identificador de la película publicada.
     */
    public function movieId(): MovieId
    {
        return $this->movieId;
    }

    /**
     * Retorna el título de la película publicada.
     */
    public function title(): Title
    {
        return $this->title;
    }

    /**
     * Retorna el estado anterior de la película.
     */
    public function previousStatus(): MovieStatus
    {
        return $this->previousStatus;
    }

    /**
     * Retorna el nuevo estado de la película (PUBLISHED).
     */
    public function newStatus(): MovieStatus
    {
        return $this->newStatus;
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
            'previous_status' => $this->previousStatus->value,
            'new_status' => $this->newStatus->value,
        ]);
    }
}
