<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando una Reserva (Booking) en la base de datos.
 */
class Booking extends Model
{
    protected $table = 'bookings';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'showtime_id',
        'booking_status',
        'total_amount',
        'currency',
        'expires_at',
        'confirmed_at',
        'cancelled_at',
        'metadata',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'booking_status' => 'string',
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtime_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id', 'id');
    }
}
