<?php

<<<<<<< HEAD
namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Theater\ValueObjects\AuditoriumStatus;
use App\Infrastructure\Persistence\Eloquent\Models\Auditorium as AuditoriumModel;
use App\Infrastructure\Persistence\Mappers\AuditoriumMapper;
=======
/**
 * Implementación Eloquent del repositorio de auditoriums.
 *
 * Esta clase implementa la interfaz AuditoriumRepository y proporciona
 * la implementación concreta para persistir y recuperar objetos de dominio
 * Auditorium utilizando el ORM Eloquent de Laravel. Maneja la conversión
 * entre objetos de dominio y modelos de base de datos a través del
 * AuditoriumMapper.
 *
 * Proporciona métodos para guardar, buscar, eliminar y listar auditoriums
 * según diversos criterios como estado.
 *
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository Interfaz que implementa esta clase
 */

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Theater\Aggregates\Auditorium\Auditorium;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;
use App\Domain\Theater\Repositories\AuditoriumRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Auditorium as EloquentAuditorium;
use App\Infrastructure\Persistence\Eloquent\Models\Seat as EloquentSeat;
use App\Infrastructure\Persistence\Mappers\AuditoriumMapper;
use App\Infrastructure\Persistence\Mappers\SeatMapper;
use Illuminate\Support\Facades\DB;
>>>>>>> develop

class EloquentAuditoriumRepository implements AuditoriumRepository
{
    public function save(Auditorium $auditorium): void
    {
<<<<<<< HEAD
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
=======
        DB::transaction(function () use ($auditorium) {
            $model = AuditoriumMapper::toEloquent($auditorium);
            $model->save();
            
            // Handle seats
            EloquentSeat::where('auditorium_id', $auditorium->id()->value())->delete();
            
            foreach ($auditorium->seats() as $seat) {
                $seatModel = SeatMapper::toEloquent($seat, $auditorium->id()->value());
                $seatModel->save();
            }
        });
    }
    
    public function findById(AuditoriumId $id): ?Auditorium
    {
        $model = EloquentAuditorium::with('seats')->find($id->value());
        
        if (!$model) {
            return null;
        }
        
        return AuditoriumMapper::toDomain($model);
    }
    
    public function delete(Auditorium $auditorium): void
    {
        EloquentAuditorium::where('id', $auditorium->id()->value())->delete();
    }
    
    public function findAll(): array
    {
        $models = EloquentAuditorium::with('seats')->get();
        
        return $models->map(fn($model) => AuditoriumMapper::toDomain($model))->toArray();
    }
    
    public function findByStatus(AuditoriumStatus $status): array
    {
        $models = EloquentAuditorium::with('seats')->where('status', $status->value)->get();
        
        return $models->map(fn($model) => AuditoriumMapper::toDomain($model))->toArray();
    }
}
>>>>>>> develop
