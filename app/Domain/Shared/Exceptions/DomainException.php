<?php

/**
 * Clase base abstracta para todas las excepciones del dominio.
 *
 * Esta clase sirve como base para todas las excepciones específicas del dominio,
 * estableciendo un patrón común para el manejo de errores en la capa de dominio.
 *
 * Las excepciones del dominio representan violaciones de las reglas de negocio
 * y deberían ser capturadas y manejadas en la capa de aplicación o en los
 * controladores, nunca en el dominio mismo.
 *
 * Ejemplos de excepciones derivadas:
 * - \App\Domain\Catalog\Exceptions\InvalidMovieId
 * - \App\Domain\Catalog\Exceptions\InvalidMovieTitle
 * - \App\Domain\Catalog\Exceptions\InvalidMovieStatus
 *
 * @extends Exception Clase base de excepciones de PHP
 */

namespace App\Domain\Shared\Exceptions;

use Exception;

abstract class DomainException extends Exception
{}
