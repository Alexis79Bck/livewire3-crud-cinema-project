<?php

/**
 * Command (Comando) para crear un nuevo auditorio en el sistema.
 *
 * Este comando representa la intención de crear un nuevo auditorio y contiene
 * todos los datos necesarios para realizar esta operación. Sigue el patrón
 * CQRS (Command Query Responsibility Segregation) para separar las operaciones
 * de escritura de las de lectura.
 *
 * Los datos incluidos son:
 * - name: Nombre del auditorio
 * - capacity: Capacidad total de asientos
 * - location: Ubicación física dentro del cine
 *
 * Este comando es inmutable y se utiliza junto con el CreateAuditoriumHandler
 * para procesar la creación de auditorios.
 *
 * @see CreateAuditoriumHandler Handler que procesa este comando
 */

namespace App\Application\Theater\Commands;

class CreateAuditoriumCommand
{
    public function __construct(
        public readonly string $name,
        public readonly int $capacity,
        public readonly string $location
    ) {}
}