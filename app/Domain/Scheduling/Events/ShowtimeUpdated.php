<?php

namespace App\Domain\Scheduling\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Scheduling\ValueObjects\Schedule;

/**
 * Evento de dominio que se dispara cuando se actualiza la programación de un showtime.
 */
final class ShowtimeUpdated extends DomainEvent
{
    public function __construct(
        private ShowtimeId $showtimeId,
        private Schedule $oldSchedule,
        private Schedule $newSchedule,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function showtimeId(): ShowtimeId
    {
        return $this->showtimeId;
    }

    public function oldSchedule(): Schedule
    {
        return $this->oldSchedule;
    }

    public function newSchedule(): Schedule
    {
        return $this->newSchedule;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'showtime_id' => $this->showtimeId->value(),
            'old_start_time' => $this->oldSchedule->startTime()->format('Y-m-d H:i:s'),
            'old_end_time' => $this->oldSchedule->endTime()->format('Y-m-d H:i:s'),
            'new_start_time' => $this->newSchedule->startTime()->format('Y-m-d H:i:s'),
            'new_end_time' => $this->newSchedule->endTime()->format('Y-m-d H:i:s'),
        ]);
    }
}
