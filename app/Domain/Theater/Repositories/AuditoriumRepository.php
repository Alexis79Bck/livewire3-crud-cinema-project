<?php

namespace App\Domain\Theater\Repositories;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;

/**
 * Interfaz del Repository para el Aggregate Auditorium.
 *
 * Define el contrato para la persistencia y recuperación de auditorios en el sistema.
 * Esta interfaz sigue el patrón Repository de Domain-Driven Design, aislando
 * la lógica de dominio de los detalles de implementación de la capa de persistencia.
 *
 * Los métodos definidos permiten:
 * - Guardar un auditorio (crear o actualizar)
 * - Buscar un auditorio por su identificador único
 * - Eliminar un auditorio del sistema
 * - Listar auditorios por estado
 *
 * Las implementaciones concretas de esta interfaz deben manejar la persistencia
 * en la base de datos o cualquier otro medio de almacenamiento.
 *
 * @see Auditorium Aggregate Root que representa un auditorio
 * @see AuditoriumId Value Object que representa el identificador de un auditorio
 */
interface AuditoriumRepository
{
    public function save(Auditorium $auditorium): void;

    public function findById(AuditoriumId $id): ?Auditorium;

    public function delete(Auditorium $auditorium): void;

    /**
     * Retorna todos los auditorios del sistema.
     *
     * @return Auditorium[] Array de auditorios
     */
    public function findAll(): array;

    /**
     * Retorna todos los auditorios con un estado específico.
     *
     * @param AuditoriumStatus $status Estado de los auditorios a buscar
     * @return Auditorium[] Array de auditorios con el estado especificado
     */
    public function findByStatus(AuditoriumStatus $status): array;
}
