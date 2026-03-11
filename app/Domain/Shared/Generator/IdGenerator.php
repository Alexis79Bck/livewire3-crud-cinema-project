<?php

/**
 * Interfaz para el generador de identificadores únicos.
 *
 * Define el contrato para generar identificadores únicos en el sistema.
 * Esta interfaz sigue el patrón Dependency Inversion de SOLID, permitiendo
 * que las clases del dominio dependan de esta abstracción en lugar de
 * implementaciones concretas.
 *
 * Las implementaciones concretas pueden utilizar diferentes estrategias
 * para generar identificadores, tales como:
 * - UUID (Universal Unique Identifier)
 * - IDs incrementales de base de datos
 * - Identificadores basados en timestamps
 * - Etc.
 *
 * Esta separación permite cambiar la estrategia de generación de IDs
 * sin modificar el código que los utiliza.
 *
 * @see \App\Infrastructure\Shared\Generator\UuidGenerator Implementación concreta con UUID
 */

namespace App\Domain\Shared\Generator;

interface IdGenerator
{
    public function generate(): string;
}
