<?php

/**
 * Query para obtener una película por su identificador único.
 *
 * Este query representa una solicitud de lectura para recuperar los datos
 * de una película específica utilizando su identificador único. Forma parte
 * del patrón CQRS (Command Query Responsibility Segregation) que separa
 * las operaciones de lectura de las de escritura.
 *
 * El query contiene únicamente los datos necesarios para ejecutar la consulta:
 * - Identificador de la película a buscar
 *
 * Este patrón permite:
 * - Separar claramente las operaciones de lectura y escritura
 * - Optimizar las consultas de lectura
 * - Facilitar el caching de resultados
 * - Mejorar la mantenibilidad del código
 *
 * @see \App\Application\Catalog\Movie\Handlers\GetMovieByIdHandler Handler que procesa este query
 * @see \App\Domain\Catalog\Repositories\MovieRepository Repositorio utilizado
 */

namespace App\Application\Catalog\Movie\Queries;

final class GetMovieByIdQuery
{
    public function __construct(
        private string $movieId
    ) {}

    /**
     * Retorna el identificador de la película a buscar.
     */
    public function movieId(): string
    {
        return $this->movieId;
    }
}
