<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent representando un Ticket (Entrada) en la base de datos.
 */
class Ticket extends Model
{
    protected $table = 'tickets';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'booking_id',
        'seat_id',
        'showtime_id',
        'ticket_status',
        'price',
        'currency',
        'qr_code',
        'checksum',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'ticket_status' => 'string',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'id');
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtime_id', 'id');
    }
}
