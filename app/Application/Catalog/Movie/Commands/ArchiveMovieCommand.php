<?php

/**
 * Command (Comando) para archivar una película en el sistema.
 *
 * Este comando representa la intención de archivar una película existente.
 * Archivar una película la marca como inactiva, eliminandola de los
 * catalogos publicos pero manteniendo los datos historicos para reportes
 * y ventas de entradas ya realizadas.
 *
 * Sigue el patron CQRS (Command Query Responsibility Segregation) para
 * separar las operaciones de escritura de las de lectura.
 *
 * Este comando es inmutable y se utiliza junto con el ArchiveMovieHandler
 * para procesar el archivado de peliculas.
 *
 */

namespace App\Application\Catalog\Movie\Commands;

class ArchiveMovieCommand
{
    public function __construct(
        public readonly string $movieId
    ) {}
}
