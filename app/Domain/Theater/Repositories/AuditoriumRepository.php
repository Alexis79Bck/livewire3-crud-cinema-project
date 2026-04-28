<?php

namespace App\Domain\Theater\Repositories;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
<<<<<<< HEAD
use App\Domain\Theater\ValueObjects\AuditoriumId;

/**
 * Interface del repositorio para el Aggregate Auditorium.
 *
 * Define el contrato para la persistencia y recuperación de auditorios
 * en el sistema. Esta interfaz sigue el patrón Repository de DDD, aislando
 * la lógica de dominio de los detalles de implementación de persistencia.
 */
interface AuditoriumRepository
{
    /**
     * Guarda o actualiza un auditorium en el repositorio.
     */
    public function save(Auditorium $auditorium): void;

    /**
     * Busca un auditorium por su identificador.
     *
     * @return Auditorium|null El auditorium encontrado o null si no existe
     */
    public function findById(AuditoriumId $id): ?Auditorium;

    /**
     * Elimina un auditorium del repositorio.
     */
    public function delete(Auditorium $auditorium): void;

    /**
     * Retorna todos los auditorios.
=======
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
>>>>>>> develop
     *
     * @return Auditorium[] Array de auditorios
     */
    public function findAll(): array;

    /**
<<<<<<< HEAD
     * Retorna los auditorios activos.
     *
     * @return Auditorium[] Array de auditorios activos
     */
    public function findActive(): array;

    /**
     * Retorna un auditorio con sus asientos cargados.
     *
     * @return Auditorium|null El auditorium con asientos o null si no existe
     */
    public function findByIdWithSeats(AuditoriumId $id): ?Auditorium;
=======
     * Retorna todos los auditorios con un estado específico.
     *
     * @param AuditoriumStatus $status Estado de los auditorios a buscar
     * @return Auditorium[] Array de auditorios con el estado especificado
     */
    public function findByStatus(AuditoriumStatus $status): array;
>>>>>>> develop
}
