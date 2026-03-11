<?php

/**
 * Implementación falsa de pasarela de pagos para pruebas y desarrollo.
 *
 * Esta clase simula el comportamiento de una pasarela de pagos real
 * sin realizar transacciones monetarias reales. Es útil para:
 * - Entornos de desarrollo y pruebas
 * - Testing unitario e integración
 * - Demostraciones
 * - Entornos donde no se desea procesar pagos reales
 *
 * La clase implementa una interfaz de pasarela de pagos y retorna
 * respuestas exitosas predefinidas para todas las transacciones.
 *
 * @note Esta implementación NO debe usarse en producción
 */

namespace App\Infrastructure\Services;

class FakePaymentGateway
{

}
