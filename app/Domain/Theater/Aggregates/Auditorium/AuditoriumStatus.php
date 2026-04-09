<?php

/**
 * Enum que representa los posibles estados de un auditorio en el sistema.
 *
 * Define el ciclo de vida operativo de un auditorio:
 * - ACTIVE: Auditorio activo y disponible para programación
 * - MAINTENANCE: Auditorio en mantenimiento, no disponible temporalmente
 * - CLOSED: Auditorio cerrado permanentemente
 *
 * Este enum se utiliza para controlar la disponibilidad
 * de los auditorios en el sistema de programación del cine.
 */

namespace App\Domain\Theater\Aggregates\Auditorium;

enum AuditoriumStatus: string
{
    case ACTIVE = 'active';
    case MAINTENANCE = 'maintenance';
    case CLOSED = 'closed';
}
