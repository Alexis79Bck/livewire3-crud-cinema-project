<?php

namespace App\Domain\Theater\Repositories;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
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
     *
     * @return Auditorium[] Array de auditorios
     */
    public function findAll(): array;

    /**
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
}
