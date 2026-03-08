<?php

/**
 * Modelo Eloquent representando una Función (Showtime) en la base de datos.
 *
 * Esta clase mapea la tabla 'showtimes' y contiene la estructura de datos
 * necesaria para representar una función de cine dentro del sistema de persistencia.
 * Una función representa una proyección específica de una película en un
 * auditorium determinado, con horario definido.
 *
 * @property string $id Identificador único de la función
 * @property string $movie_id Identificador de la película que se exhibe
 * @property string $auditorium_id Identificador del auditorium donde se exhibe
 * @property \Carbon\Carbon $start_time Hora de inicio de la función
 * @property \Carbon\Carbon $end_time Hora de fin de la función
 * @property string $status Estado de la función (disponible, completa, cancelada, etc.)
 * @property float $base_price Precio base de las entradas
 */

namespace App\Infrastructure\Persistence\Eloquent\Models;

class Showtime
{

}
