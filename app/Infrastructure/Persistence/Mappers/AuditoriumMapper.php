<?php

<<<<<<< HEAD
namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\ValueObjects\AuditoriumName;
use App\Domain\Theater\ValueObjects\AuditoriumStatus;
use App\Domain\Theater\Aggregates\Auditorium\SeatEntity;
use App\Domain\Theater\ValueObjects\SeatNumber;
use App\Domain\Theater\ValueObjects\SeatType;
use App\Infrastructure\Persistence\Eloquent\Models\Auditorium as AuditoriumModel;
use App\Domain\Theater\Aggregates\Auditorium\Auditorium;

/**
 * Mapper para convertir entre el aggregate Auditorium y el modelo Eloquent.
 */
class AuditoriumMapper
{
    public static function toDomain(AuditoriumModel $model): Auditorium
    {
        $seats = [];
        foreach ($model->seats as $seatModel) {
            $seats[] = new SeatEntity(
                new SeatNumber($seatModel->row, $seatModel->number),
                SeatType::from($seatModel->type)
            );
        }

        return Auditorium::reconstitute(
            new AuditoriumId($model->id),
            new AuditoriumName($model->name),
            $model->capacity,
            AuditoriumStatus::from($model->is_active ? 'active' : 'inactive'),
            $seats
        );
    }

    public static function toEloquent(Auditorium $auditorium): AuditoriumModel
    {
        $model = AuditoriumModel::find($auditorium->id()->value()) ?? new AuditoriumModel();

        $model->id = $auditorium->id()->value();
        $model->name = $auditorium->name()->value();
        $model->capacity = $auditorium->capacity();
        $model->is_active = $auditorium->status()->isActive();

        return $model;
    }
}
=======
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
>>>>>>> develop
