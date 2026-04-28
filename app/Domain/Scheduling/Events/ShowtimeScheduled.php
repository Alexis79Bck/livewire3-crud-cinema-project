<?php

namespace App\Domain\Scheduling\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Scheduling\ValueObjects\Schedule;

/**
 * Evento de dominio que se dispara cuando se programa un nuevo showtime.
 */
final class ShowtimeScheduled extends DomainEvent
{
    public function __construct(
        private ShowtimeId $showtimeId,
        private Schedule $schedule,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function showtimeId(): ShowtimeId
    {
        return $this->showtimeId;
    }

    public function schedule(): Schedule
    {
        return $this->schedule;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'showtime_id' => $this->showtimeId->value(),
            'start_time' => $this->schedule->startTime()->format('Y-m-d H:i:s'),
            'end_time' => $this->schedule->endTime()->format('Y-m-d H:i:s'),
        ]);
    }
}
