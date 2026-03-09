<?php

/**
 * Command (Comando) para crear una nueva película en el sistema.
 *
 * Este comando representa la intención de crear una nueva película y contiene
 * todos los datos necesarios para realizar esta operación. Sigue el patrón
 * CQRS (Command Query Responsibility Segregation) para separar las operaciones
 * de escritura de las de lectura.
 *
 * Los datos incluidos son:
 * - title: Título de la película
 * - plot: Sinopsis o trama de la película
 * - releaseDate: Fecha de estreno de la película
 * - rating: Clasificación de edad de la película
 * - image: URL de la imagen/póster de la película
 *
 * Este comando es inmutable y se utiliza junto con el CreateMovieHandler
 * para procesar la creación de películas.
 *
 * @see CreateMovieHandler Handler que procesa este comando
 */

namespace App\Application\Catalog\Movie\Commands;

use DateTimeImmutable;


class CreateMovieCommand
{
    public function __construct(
        public readonly string $title,
        public readonly string $plot,
        public readonly DateTimeImmutable $releaseDate,
        public readonly string $rating,
        public readonly string $image
    ) {}
}
