<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;
use DateTimeImmutable;
use DateTimeInterface;
use DateInterval;

/**
 * Value Object que representa un rango de fechas.
 *
 * Encapsula una fecha de inicio y una fecha de fin, asegurando que:
 * - La fecha de inicio sea anterior a la fecha de fin
 * - Las fechas sean válidas
 *
 * Esta clase es inmutable: una vez creada, sus valores no pueden cambiar.
 * Proporciona métodos para verificar solapamientos entre rangos de fechas.
 */
final class DateRange
{
    private DateTimeImmutable $startDate;
    private DateTimeImmutable $endDate;

    public function __construct(
        DateTimeInterface $startDate,
        DateTimeInterface $endDate
    ) {
        $this->startDate = new DateTimeImmutable($startDate->format('Y-m-d H:i:s'));
        $this->endDate = new DateTimeImmutable($endDate->format('Y-m-d H:i:s'));
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->startDate >= $this->endDate) {
            throw DomainException::invalidDateRange(
                $this->startDate,
                $this->endDate
            );
        }
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function overlapsWith(DateRange $other): bool
    {
        return $this->startDate < $other->endDate && $this->endDate > $other->startDate;
    }

    public function contains(DateTimeInterface $date): bool
    {
        $checkDate = new DateTimeImmutable($date->format('Y-m-d H:i:s'));
        return $checkDate >= $this->startDate && $checkDate <= $this->endDate;
    }

    public function duration(): DateInterval
    {
        return $this->startDate->diff($this->endDate);
    }

    public function isPast(): bool
    {
        $now = new DateTimeImmutable();
        return $this->endDate < $now;
    }

    public function isFuture(): bool
    {
        $now = new DateTimeImmutable();
        return $this->startDate > $now;
    }

    public function isInPastHours(int $hours): bool
    {
        $now = new DateTimeImmutable();
        $past = $now->sub(new DateInterval('PT' . $hours . 'H'));
        return $this->endDate < $past;
    }

    public function isInFutureHours(int $hours): bool
    {
        $now = new DateTimeImmutable();
        $future = $now->add(new DateInterval('PT' . $hours . 'H'));
        return $this->startDate > $future;
    }

    public function equals(DateRange $other): bool
    {
        return $this->startDate == $other->startDate && $this->endDate == $other->endDate;
    }

    public function toArray(): array
    {
        return [
            'start_date' => $this->startDate->format('Y-m-d H:i:s'),
            'end_date' => $this->endDate->format('Y-m-d H:i:s'),
        ];
    }
}
