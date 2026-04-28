<?php

/**
 * Command (Comando) para actualizar un auditorio existente en el sistema.
 *
 * Este comando representa la intención de actualizar un auditorio y contiene
 * todos los datos necesarios para realizar esta operación. Sigue el patrón
 * CQRS (Command Query Responsibility Segregation) para separar las operaciones
 * de escritura de las de lectura.
 *
 * Los datos incluidos son:
 * - id: Identificador del auditorio a actualizar
 * - name: Nuevo nombre del auditorio (opcional)
 * - capacity: Nueva capacidad total de asientos (opcional)
 * - location: Nueva ubicación física dentro del cine (opcional)
 *
 * Este comando es inmutable y se utiliza junto con el UpdateAuditoriumHandler
 * para procesar la actualización de auditorios.
 *
 * @see UpdateAuditoriumHandler Handler que procesa este comando
 */

namespace App\Application\Theater\Commands;

class UpdateAuditoriumCommand
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name = null,
        public readonly ?int $capacity = null,
        public readonly ?string $location = null
    ) {}
}