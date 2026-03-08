<?php

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
