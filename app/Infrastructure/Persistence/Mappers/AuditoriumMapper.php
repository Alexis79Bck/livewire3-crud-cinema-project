<?php

/**
 * Mapper para convertir entre objetos de dominio Auditorium y modelos Eloquent.
 *
 * Esta clase proporciona métodos estáticos para transformar objetos de dominio
 * Auditorium (capa de dominio) a modelos Eloquent Auditorium (capa de infraestructura)
 * y viceversa. Maneja la traducción de datos entre las dos capas manteniendo
 * la separación limpia del dominio.
 *
 * El patrón Mapper es parte de la arquitectura de infraestructura y permite
 * que el dominio permanezca libre de dependencias de frameworks externos.
 * Utiliza los Value Objects del dominio para crear instancias de Auditorium.
 *
 * @see \App\Domain\Theater\Aggregates\Auditorium\Auditorium Modelo de dominio
 * @see \App\Infrastructure\Persistence\Eloquent\Models\Auditorium Modelo Eloquent
 */

namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;
use App\Infrastructure\Persistence\Eloquent\Models\Auditorium as EloquentAuditorium;
use App\Infrastructure\Persistence\Mappers\SeatMapper;

class AuditoriumMapper
{
    public static function toDomain(EloquentAuditorium $model): Auditorium
    {
        // Convert seats if they exist
        $seats = [];
        if ($model->relationLoaded('seats')) {
            $seats = $model->seats->map(fn($seat) => SeatMapper::toDomain($seat))->toArray();
        }
        
        return Auditorium::reconstitute(
            AuditoriumId::fromString($model->id),
            $model->name,
            $model->capacity,
            $model->location,
            AuditoriumStatus::from($model->status),
            $seats
        );
    }
    
    public static function toEloquent(Auditorium $auditorium): EloquentAuditorium
    {
        $model = EloquentAuditorium::find($auditorium->id()->value()) ?? new EloquentAuditorium();
        
        $model->id = $auditorium->id()->value();
        $model->name = $auditorium->name();
        $model->capacity = $auditorium->capacity();
        $model->location = $auditorium->location();
        $model->status = $auditorium->status()->value;
        
        return $model;
    }
}