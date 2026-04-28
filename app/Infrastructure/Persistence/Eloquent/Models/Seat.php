<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando un Asiento en la base de datos.
 */
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
        'number',
        'type',
        'position_x',
        'position_y',
    ];

    protected $casts = [
        'position_x' => 'integer',
        'position_y' => 'integer',
    ];

    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class, 'auditorium_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'seat_id', 'id');
    }
}
