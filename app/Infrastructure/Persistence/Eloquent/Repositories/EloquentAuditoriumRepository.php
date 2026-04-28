<?php

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

class EloquentAuditoriumRepository implements AuditoriumRepository
{
    public function save(Auditorium $auditorium): void
    {
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