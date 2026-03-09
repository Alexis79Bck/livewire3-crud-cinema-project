<?php

/**
 * Modelo Eloquent representando un Ticket (Entrada) en la base de datos.
 *
 * Esta clase mapea la tabla 'tickets' y contiene la estructura de datos
 * necesaria para representar una entrada de cine dentro del sistema de persistencia.
 * Un ticket representa la compra efectiva de un asiento específico para una
 * función determinada, incluyendo los detalles del pago.
 *
 * @property string $id Identificador único del ticket
 * @property string $booking_id Identificador de la reserva asociada
 * @property string $showtime_id Identificador de la función
 * @property string $seat_id Identificador del asiento reservado
 * @property float $price Precio del ticket
 * @property string $status Estado del ticket (vendido, usado, cancelado, etc.)
 * @property \Carbon\Carbon $purchase_date Fecha de compra del ticket
 */

namespace App\Infrastructure\Persistence\Eloquent\Models;

class Ticket
{

}
