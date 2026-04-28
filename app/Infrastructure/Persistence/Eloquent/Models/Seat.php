<?php

/**
 * Modelo Eloquent representando un Asiento (Seat) en la base de datos.
 *
 * Esta clase mapea la tabla 'seats' y contiene la estructura de datos
 * necesaria para representar un asiento dentro de un auditorium en el sistema
 * de persistencia. Cada asiento pertenece a un auditorium específico y tiene
 * características como fila, número y tipo.
 *
 * @property string $id Identificador único del asiento
 * @property string $auditorium_id Identificador del auditorium al que pertenece
 * @property string $row Letra o identificador de la fila del asiento
 * @property int $seat_number Número del asiento dentro de la fila
 * @property string $type Tipo de asiento (estándar, premium, VIP, etc.)
 * @property bool $is_available Indica si el asiento está disponible
 */

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'auditorium_id',
        'row',
        'seat_number',
        'type',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'seat_number' => 'integer'
    ];

    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }
}
