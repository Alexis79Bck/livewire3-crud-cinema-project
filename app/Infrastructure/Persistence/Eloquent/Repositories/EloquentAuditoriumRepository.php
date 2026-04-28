<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Theater\ValueObjects\AuditoriumStatus;
use App\Infrastructure\Persistence\Eloquent\Models\Auditorium as AuditoriumModel;
use App\Infrastructure\Persistence\Mappers\AuditoriumMapper;

class EloquentAuditoriumRepository implements AuditoriumRepository
{
    public function save(Auditorium $auditorium): void
    {
        $model = AuditoriumMapper::toEloquent($auditorium);
        $model->save();

        // Save seats if they were modified
        if (!empty($auditorium->releaseEvents())) {
            $this->syncSeats($auditorium, $model);
        }
    }

    public function findById(AuditoriumId $id): ?Auditorium
    {
        $model = AuditoriumModel::find($id->value());

        if (!$model) {
            return null;
        }

        return AuditoriumMapper::toDomain($model);
    }

    public function delete(Auditorium $auditorium): void
    {
        AuditoriumModel::where('id', $auditorium->id()->value())->delete();
    }

    public function findAll(): array
    {
        $models = AuditoriumModel::all();

        return $models
            ->map(fn (AuditoriumModel $model) => AuditoriumMapper::toDomain($model))
            ->toArray();
    }

    public function findActive(): array
    {
        $models = AuditoriumModel::where('is_active', true)->get();

        return $models
            ->map(fn (AuditoriumModel $model) => AuditoriumMapper::toDomain($model))
            ->toArray();
    }

    public function findByIdWithSeats(AuditoriumId $id): ?Auditorium
    {
        $model = AuditoriumModel::with('seats')->find($id->value());

        if (!$model) {
            return null;
        }

        return AuditoriumMapper::toDomain($model);
    }

    private function syncSeats(Auditorium $auditorium, AuditoriumModel $model): void
    {
        // In a real implementation, you would sync seats
        // For simplicity, we rely on the mapper to save seats separately
    }
}
