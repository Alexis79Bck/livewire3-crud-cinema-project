<?php

namespace App\Domain\Scheduling\Aggregates\Showtime\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Excepción que se lanza cuando se intenta programar un showtime
 * con una hora de inicio que no es anterior a la hora de fin.
 */
class InvalidShowtimeSchedule extends DomainException
{
    public static function endTimeBeforeStartTime(): self
    {
        return new self('Showtime end time must be after start time.');
    }
}
