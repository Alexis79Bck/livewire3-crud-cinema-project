<?php

namespace App\Domain\Scheduling\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;
use App\Domain\Shared\ValueObjects\DateRange;
use DateTimeImmutable;

/**
 * Value Object que representa una agenda de showtime (fecha/hora de función).
 */
final class Schedule
{
    private DateTimeImmutable $startTime;
    private DateTimeImmutable $endTime;

    public function __construct(
        DateTimeImmutable $startTime,
        DateTimeImmutable $endTime
    ) {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->startTime >= $this->endTime) {
            throw DomainException::invalidSchedule(
                $this->startTime,
                $this->endTime
            );
        }
    }

    public function startTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function endTime(): DateTimeImmutable
    {
        return $this->endTime;
    }

    public function overlapsWith(Schedule $other): bool
    {
        return $this->startTime < $other->endTime && $this->endTime > $other->startTime;
    }

    public function isPast(): bool
    {
        $now = new DateTimeImmutable();
        return $this->endTime < $now;
    }

    public function isFuture(): bool
    {
        $now = new DateTimeImmutable();
        return $this->startTime > $now;
    }

    public function isUpcoming(int $minutes = 30): bool
    {
        $now = new DateTimeImmutable();
        $upcoming = $now->add(new \DateInterval('PT' . $minutes . 'M'));
        return $this->startTime > $now && $this->startTime <= $upcoming;
    }

    /**
     * Verifica si el showtime ya ha comenzado hace al menos X horas.
     */
    public function isCompletedHoursAgo(int $hours): bool
    {
        $now = new DateTimeImmutable();
        $past = $now->sub(new \DateInterval('PT' . $hours . 'H'));
        return $this->endTime < $past;
    }

    public function equals(Schedule $other): bool
    {
        return $this->startTime == $other->startTime && $this->endTime == $other->endTime;
    }

    public function toDateRange(): DateRange
    {
        return new DateRange($this->startTime, $this->endTime);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s to %s',
            $this->startTime->format('Y-m-d H:i'),
            $this->endTime->format('Y-m-d H:i')
        );
    }
}
