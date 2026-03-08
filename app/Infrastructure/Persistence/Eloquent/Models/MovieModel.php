<?php

/**
 * Modelo Eloquent representando una Película en la base de datos.
 *
 * Esta clase mapea la tabla 'movies' y contiene la estructura de datos
 * necesaria para representar una película dentro del sistema de persistencia.
 * El modelo utiliza Eloquent de Laravel para interacting con la base de datos
 * y proporciona métodos para manipular los datos de las películas.
 *
 * @property string $id Identificador único de la película
 * @property string $title Título de la película
 * @property string $plot Sinopsis o descripción de la película
 * @property \Carbon\Carbon $release_date Fecha de estreno de la película
 * @property float $rating Calificación de la película (0-10)
 * @property string $image URL o path de la imagen promocional
 * @property string $status Estado de la película (activa, archivada, próxima, etc.)
 */

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class MovieModel extends Model
{
    protected $table = 'movies';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'plot',
        'release_date',
        'rating',
        'image',
        'status'
    ];

    protected $casts = [
        'release_date' => 'date',
        'rating' => 'float',
        'status' => 'string'
    ];

}
