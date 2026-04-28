<?php

/**
 * Mapper para convertir entre objetos de dominio Seat y modelos Eloquent.
 *
 * Esta clase proporciona métodos estáticos para transformar objetos de dominio
 * Seat (capa de dominio) a modelos Eloquent Seat (capa de infraestructura)
 * y viceversa. Maneja la traducción de datos entre las dos capas manteniendo
 * la separación limpia del dominio.
 *
 * @see \App\Domain\Theater\Aggregates\Auditorium\Seat Modelo de dominio
 * @see \App\Infrastructure\Persistence\Eloquent\Models\Seat Modelo Eloquent
 */

namespace App\Infrastructure\Persistence\Mappers;

use App\Domain\Theater\Aggregates\Auditorium\Seat;
use App\Domain\Theater\Aggregates\Auditorium\SeatNumber;
use App\Domain\Shared\Enums\SeatType;
use App\Infrastructure\Persistence\Eloquent\Models\Seat as EloquentSeat;

class SeatMapper
{
    public static function toDomain(EloquentSeat $model): Seat
    {
        // Parse the seat number format (RowLetter-Number)
        $parts = explode('-', $model->row . '-' . $model->seat_number);
        $seatNumberValue = count($parts) == 2 ? $parts[0] . '-' . $parts[1] : 'A-1';
        
        return Seat::create(
            SeatNumber::create($seatNumberValue),
            SeatType::from($model->type)
        );
    }
    
    public static function toEloquent(Seat $seat, string $auditoriumId): EloquentSeat
    {
        $model = new EloquentSeat();
        $model->id = self::generateUuid();
        $model->auditorium_id = $auditoriumId;
        
        // Extract row and number from the seat number value
        $seatNumberValue = $seat->seatNumber()->toString();
        $parts = explode('-', $seatNumberValue);
        $model->row = count($parts) > 0 ? $parts[0] : 'A';
        $model->seat_number = count($parts) > 1 ? (int)$parts[1] : 1;
        
        $model->type = $seat->type()->value;
        $model->is_available = true;
        
        return $model;
    }
    
    private static function generateUuid(): string
    {
        return \Illuminate\Support\Str::uuid()->toString();
    }
}