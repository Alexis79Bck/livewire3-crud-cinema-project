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

<<<<<<< HEAD
/**
 * Enum que representa los diferentes tipos de asientos disponibles
 * en las salas de cine.
 *
 * Define las categorías de asientos que afectan el precio y la
 * experiencia del cliente:
 * - STANDARD: Asientos regulares de la sala
 * - VIP: Asientos premium con mayor comodidad
 * - ACCESSIBLE: Asientos adaptados para personas con movilidad reducida
 * - DISABLED: Asientos temporalmente no disponibles
 */
enum SeatType: string
{
    case STANDARD = 'standard';
    case VIP = 'vip';
    case ACCESSIBLE = 'accessible';
    case DISABLED = 'disabled';

    /**
     * Retorna una etiqueta legible para el tipo de asiento.
     */
    public function label(): string
    {
        return match($this) {
            self::STANDARD => 'Estándar',
            self::VIP => 'VIP',
            self::ACCESSIBLE => 'Accesible',
            self::DISABLED => 'No Disponible',
        };
    }

    /**
     * Verifica si el tipo de asiento está activo/disponible.
     */
    public function isActive(): bool
    {
        return $this !== self::DISABLED;
    }
=======
enum SeatType: string
{
    case STANDARD = 'standard';
    case PREMIUM = 'premium';
    case VIP = 'vip';
    case ACCESSIBLE = 'accessible';
>>>>>>> develop
}
