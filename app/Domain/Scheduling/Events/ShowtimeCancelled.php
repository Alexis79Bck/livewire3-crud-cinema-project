<?php

namespace App\Domain\Scheduling\Events;

use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;

/**
 * Evento de dominio que se dispara cuando se cancela un showtime.
 */
final class ShowtimeCancelled extends DomainEvent
{
    public function __construct(
        private ShowtimeId $showtimeId,
        array $metadata = []
    ) {
        parent::__construct($metadata);
    }

    public function showtimeId(): ShowtimeId
    {
        return $this->showtimeId;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'showtime_id' => $this->showtimeId->value(),
        ]);
    }
}
