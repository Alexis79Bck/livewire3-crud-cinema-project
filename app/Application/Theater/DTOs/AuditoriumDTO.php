<?php

/**
 * Data Transfer Object para transferir datos de auditorios entre capas.
 *
 * Este DTO se utiliza para transferir información de auditorios desde la capa
 * de aplicación hacia la capa de presentación, evitando exponer directamente
 * los objetos de dominio. Proporciona una representación serializable y
 * segura de los datos de un auditorio.
 *
 * El DTO contiene:
 * - Identificador único del auditorio
 * - Nombre del auditorio
 * - Capacidad total de asientos
 * - Ubicación física dentro del cine
 * - Estado actual del auditorio
 * - Lista de asientos disponibles
 * - Fecha de creación
 * - Fecha de última actualización
 *
 * Este patrón permite:
 * - Desacoplar la capa de presentación del dominio
 * - Controlar qué información se expone
 * - Facilitar la serialización a JSON/XML
 * - Mejorar el rendimiento al evitar cargar relaciones innecesarias
 *
 * @see \App\Domain\Theater\Aggregates\Auditorium\Auditorium Aggregate de origen
 * @see \App\Application\Theater\Handlers\GetAuditoriumByIdHandler Handler que utiliza este DTO
 */
 
namespace App\Application\Theater\DTOs;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;

final class AuditoriumDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $capacity,
        public readonly string $location,
        public readonly string $status,
        public readonly array $seats,
        public readonly string $createdAt,
        public readonly ?string $updatedAt = null
    ) {}

    /**
     * Crea un DTO desde un objeto Auditorium del dominio.
     */
    public static function fromDomain(Auditorium $auditorium): self
    {
        // Convertir asientos a array serializable
        $seatsArray = array_map(function ($seat) {
            return [
                'number' => $seat->seatNumber()->toString(),
                'type' => $seat->type()->value,
            ];
        }, $auditorium->seats());

        return new self(
            id: $auditorium->id()->toString(),
            name: $auditorium->name(),
            capacity: $auditorium->capacity(),
            location: $auditorium->location(),
            status: $auditorium->status()->value,
            seats: $seatsArray,
            createdAt: (new \DateTime())->format('Y-m-d H:i:s'),
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
            name: $data['name'],
            capacity: $data['capacity'],
            location: $data['location'],
            status: $data['status'],
            seats: $data['seats'] ?? [],
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
            'name' => $this->name,
            'capacity' => $this->capacity,
            'location' => $this->location,
            'status' => $this->status,
            'seats' => $this->seats,
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