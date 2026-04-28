<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando una Función (Showtime) en la base de datos.
 */
class Showtime extends Model
{
    protected $table = 'showtimes';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'movie_id',
        'auditorium_id',
        'start_time',
        'end_time',
        'base_price',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function movie()
    {
        return $this->belongsTo(MovieModel::class, 'movie_id', 'id');
    }

    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class, 'auditorium_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'showtime_id', 'id');
    }

    public function tickets()
    {
        return $this->hasManyThrough(
            Ticket::class,
            Booking::class,
            'showtime_id',
            'booking_id',
            'id',
            'id'
        );
    }
}
