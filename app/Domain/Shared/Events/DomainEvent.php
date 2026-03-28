<?php

/**
 * Clase base abstracta para todos los eventos de dominio.
 *
 * Los eventos de dominio representan algo que ha ocurrido en el dominio
 * que es relevante para el negocio. Esta clase proporciona la estructura
 * común que todos los eventos deben tener, incluyendo:
 * - Identificador único del evento
 * - Marca de tiempo del momento en que ocurrió
 * - Metadatos adicionales opcionales
 *
 * Los eventos son inmutables una vez creados y se utilizan para:
 * - Comunicar cambios entre bounded contexts
 * - Implementar arquitectura orientada a eventos
 * - Permitir auditoría y reconstrucción de estados
 * - Facilitar la integración con sistemas externos
 *
 * @see \App\Domain\Catalog\Events\MovieCreated Ejemplo de evento específico
 * @see \App\Domain\Catalog\Events\MoviePublished Ejemplo de evento específico
 */

namespace App\Domain\Shared\Events;

use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

abstract class DomainEvent
{
    private UuidInterface $eventId;
    private \DateTimeImmutable $occurredOn;
    private array $metadata;

    public function __construct(array $metadata = [])
    {
        $this->eventId = Str::uuid();
        $this->occurredOn = new \DateTimeImmutable();
        $this->metadata = $metadata;
    }

    /**
     * Retorna el identificador único del evento.
     */
    public function eventId(): UuidInterface
    {
        return $this->eventId;
    }

    /**
     * Retorna la fecha y hora en que ocurrió el evento.
     */
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * Retorna los metadatos adicionales del evento.
     *
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }

    /**
     * Retorna el nombre del evento (nombre de la clase sin namespace).
     */
    public function eventName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Retorna el nombre completo del evento con namespace.
     */
    public function eventFullName(): string
    {
        return get_class($this);
    }

    /**
     * Convierte el evento a un array para serialización.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId->toString(),
            'event_name' => $this->eventName(),
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
            'metadata' => $this->metadata,
        ];
    }
}
