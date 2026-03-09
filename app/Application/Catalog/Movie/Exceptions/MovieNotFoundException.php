<?php

/**
 * Excepcion personalizada para cuando no se encuentra una pelicula.
 *
 * Esta excepcion se lanza cuando se intenta acceder a una pelicula
 * que no existe en el sistema o ha sido eliminada. Proporciona
 * un mensaje claro indicando el ID de la pelicula que no se encontro.
 *
 * Es parte del manejo de errores en la capa de aplicacion y ayuda
 * a identificar rapidamente problemas relacionados con peliculas
 * inexistentes durante operaciones de lectura o escritura.
 */

namespace App\Application\Catalog\Movie\Exceptions;

use RuntimeException;

class MovieNotFoundException extends RuntimeException
{
    public function __construct(string $movieId)
    {
        parent::__construct("Movie with id {$movieId} not found.");
    }
}
