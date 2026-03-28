<?php

/**
 * Evento de dominio que se dispara cuando se archiva una película.
 *
 * Este evento representa el archivado de una película en el catálogo del cine.
 * Se publica cuando se invoca el método `archive()` del aggregate Movie.
 *
 * El evento contiene información relevante sobre el archivado:
 * - Identificador único de la película
 * - Título de la película
 * - Estado anterior (DRAFT o PUBLISHED)
 * - Nuevo estado (ARCHIVED)
 * - Fecha de archivado
 *
 * Este evento puede ser utilizado para:
 * - Notificar a otros bounded contexts que la película ya no está disponible
 * - Remover la película de catálogos activos
 * - Cancelar reservas pendientes relacionadas
 * - Generar reportes de películas archivadas
 * - Registrar auditoría de cambios de estado
 *
 * @see \App\Domain\Catalog\Aggregates\Movie\Movie::archive() Método que publica este evento
 * @see \App\Domain\Shared\Events\DomainEvent Clase base del evento
 */

namespace App\Domain\Catalog\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Catalog\ValueObjects\Title;
use App\Domain\Catalog\Enums\MovieStatus;

final class MovieArchived extends DomainEvent
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
     * Retorna el identificador de la película archivada.
     */
    public function movieId(): MovieId
    {
        return $this->movieId;
    }

    /**
     * Retorna el título de la película archivada.
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
     * Retorna el nuevo estado de la película (ARCHIVED).
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
