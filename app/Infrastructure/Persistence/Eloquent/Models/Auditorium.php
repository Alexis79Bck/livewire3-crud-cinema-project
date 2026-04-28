<?php

<<<<<<< HEAD
=======
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
 * @property string $location Ubicación del auditorium
 * @property string $status Estado actual del auditorium (activo, mantenimiento, etc.)
 */

>>>>>>> develop
namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
/**
 * Modelo Eloquent representando un Auditorio en la base de datos.
 */
=======
>>>>>>> develop
class Auditorium extends Model
{
    protected $table = 'auditoriums';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'capacity',
<<<<<<< HEAD
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
=======
        'location',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
        'capacity' => 'integer'
>>>>>>> develop
    ];

    public function seats()
    {
<<<<<<< HEAD
        return $this->hasMany(Seat::class, 'auditorium_id', 'id');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'auditorium_id', 'id');
=======
        return $this->hasMany(Seat::class);
>>>>>>> develop
    }
}
