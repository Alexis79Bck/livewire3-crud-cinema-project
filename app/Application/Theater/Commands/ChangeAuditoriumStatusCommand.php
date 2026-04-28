<?php

/**
 * Command (Comando) para cambiar el estado de un auditorio en el sistema.
 *
 * Este comando representa la intención de cambiar el estado de un auditorio y
 * contiene todos los datos necesarios para realizar esta operación. Sigue el
 * patrón CQRS (Command Query Responsibility Segregation) para separar las
 * operaciones de escritura de las de lectura.
 *
 * Los datos incluidos son:
 * - id: Identificador del auditorio a actualizar
 * - status: Nuevo estado del auditorio
 *
 * Este comando es inmutable y se utiliza junto con el ChangeAuditoriumStatusHandler
 * para procesar el cambio de estado de auditorios.
 *
 * @see ChangeAuditoriumStatusHandler Handler que procesa este comando
 */

namespace App\Application\Theater\Commands;

class ChangeAuditoriumStatusCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $status
    ) {}
}