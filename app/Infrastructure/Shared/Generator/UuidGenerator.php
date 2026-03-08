<?php

/**
 * Implementación concreta del generador de identificadores únicos utilizando UUID.
 *
 * Esta clase implementa la interfaz IdGenerator utilizando la funcionalidad
 * de UUID de Laravel (Str::uuid) para generar identificadores únicos y universalmente
 * únicos según el estándar RFC 4122.
 *
 * Esta implementación es apropiada para entornos de producción donde se requieren
 * identificadores que:
 * - Sean únicos a nivel global
 * - No expongan información secuencial
 * - Sean difíciles de adivinar
 *
 * @see IdGenerator Interfaz que implementa esta clase
 */

namespace App\Infrastructure\Shared\Generator;

use Illuminate\Support\Str;
use App\Domain\Shared\Generator\IdGenerator;

final class UuidGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Str::uuid()->toString();
    }
}
