<?php

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
