<?php

/**
 * Modelo Eloquent representando un Auditorium (sala de cine) en la base de datos.
 *
 * Esta clase mapea la tabla 'auditoriums' y contiene la estructura de datos
 * necesaria para representar una sala de cine dentro del sistema de persistencia.
 * Un auditorium corresponde a un espacio físico donde se exhiben películas
 * y puede contener múltiples asientos organizados en filas.
 *
 * @property string $id Identificador único del auditorium
 * @property string $name Nombre del auditorium (ej: Sala 1, Sala Premium)
 * @property int $capacity Capacidad total de asientos
 * @property string $status Estado actual del auditorium (activo, mantenimiento, etc.)
 */

namespace App\Infrastructure\Persistence\Eloquent\Models;

class Auditorium
{

}
