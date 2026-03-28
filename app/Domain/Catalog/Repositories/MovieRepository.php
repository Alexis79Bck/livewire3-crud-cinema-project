<?php

namespace App\Domain\Catalog\Repositories;

use App\Domain\Catalog\Aggregates\Movie\Movie;
use App\Domain\Catalog\Enums\MovieStatus;
use App\Domain\Catalog\ValueObjects\MovieId;

/**
 * Interfaz del Repository para el Aggregate Movie.
 *
 * Define el contrato para la persistencia y recuperación de películas en el sistema.
 * Esta interfaz sigue el patrón Repository de Domain-Driven Design, aislando
 * la lógica de dominio de los detalles de implementación de la capa de persistencia.
 *
 * Los métodos definidos permiten:
 * - Guardar una película (crear o actualizar)
 * - Buscar una película por su identificador único
 * - Eliminar una película del sistema
 * - Listar películas dentro de un rango de fechas de estreno
 *
 * Las implementaciones concretas de esta interfaz deben manejar la persistencia
 * en la base de datos o cualquier otro medio de almacenamiento.
 *
 * @see Movie Aggregate Root que representa una película
 * @see MovieId Value Object que representa el identificador de una película
 */
interface MovieRepository
{
    public function save(Movie $movie): void;

    public function findById(MovieId $id): ?Movie;

    public function delete(Movie $movie): void;

    /** @return Movie[] */
    public function listByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array;

    /**
     * Retorna todas las películas del catálogo.
     *
     * @return Movie[] Array de películas
     */
    public function findAll(): array;

    /**
     * Retorna todas las películas con un estado específico.
     *
     * @param MovieStatus $status Estado de las películas a buscar
     * @return Movie[] Array de películas con el estado especificado
     */
    public function findByStatus(MovieStatus $status): array;
}
