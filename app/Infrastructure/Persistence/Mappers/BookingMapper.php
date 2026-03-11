<?php

/**
 * Mapper para convertir entre objetos de dominio Booking y modelos Eloquent.
 *
 * Esta clase proporciona métodos estáticos para transformar objetos de dominio
 * Booking (capa de dominio) a modelos Eloquent (capa de infraestructura) y
 * viceversa. Maneja la traducción de datos entre las dos capas manteniendo
 * la separación limpia del dominio.
 *
 * El patrón Mapper es parte de la arquitectura de infraestructura y permite
 * que el dominio permanezca libre de dependencias de frameworks externos.
 *
 * @see Booking Modelo de dominio
 * @see \App\Infrastructure\Persistence\Eloquent\Models\Booking Modelo Eloquent
 */

namespace App\Infrastructure\Persistence\Mappers;

class BookingMapper
{

}
