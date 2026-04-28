<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando un Auditorio en la base de datos.
 */
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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'auditorium_id', 'id');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'auditorium_id', 'id');
    }
}
