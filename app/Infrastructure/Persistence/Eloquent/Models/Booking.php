<?php

/**
 * Modelo Eloquent representando una Reserva (Booking) en la base de datos.
 *
 * Esta clase mapea la tabla 'bookings' y contiene la estructura de datos
 * necesaria para representar una reserva de entradas de cine dentro del sistema
 * de persistencia. Una reserva representa la intención de un cliente de
 * asistir a una función específica.
 *
 * @property string $id Identificador único de la reserva
 * @property string $customer_id Identificador del cliente que realiza la reserva
 * @property string $showtime_id Identificador de la función de cine
 * @property string $status Estado de la reserva (pendiente, confirmada, cancelada, etc.)
 * @property float $total_amount Monto total de la reserva
 * @property \Carbon\Carbon $created_at Fecha de creación de la reserva
 * @property \Carbon\Carbon $expires_at Fecha de expiración de la reserva
 */

namespace App\Infrastructure\Persistence\Eloquent\Models;

class Booking
{

}
