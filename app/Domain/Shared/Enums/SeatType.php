<?php

/**
 * Enum que representa los tipos de asientos disponibles en un auditorio.
 *
 * Define los diferentes tipos de asientos que pueden existir:
 * - STANDARD: Asiento estándar regular
 * - PREMIUM: Asiento premium con mejor comodidad
 * - VIP: Asiento VIP con servicios adicionales
 * - ACCESSIBLE: Asiento accesible para personas con movilidad reducida
 */

namespace App\Domain\Shared\Enums;

enum SeatType: string
{
    case STANDARD = 'standard';
    case PREMIUM = 'premium';
    case VIP = 'vip';
    case ACCESSIBLE = 'accessible';
}
