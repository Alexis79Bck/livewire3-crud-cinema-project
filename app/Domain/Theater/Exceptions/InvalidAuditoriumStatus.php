<?php

/**
 * Excepción que se lanza cuando se intenta realizar una transición de estado inválida
 * para un auditorio.
 */

namespace App\Domain\Theater\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumStatus;

final class InvalidAuditoriumStatus extends DomainException
{
    public static function cannotChangeFrom(string $fromStatus, string $toStatus): self
    {
        return new self("Cannot change auditorium status from {$fromStatus} to {$toStatus}");
    }

    public static function invalidTransition(AuditoriumStatus $current, AuditoriumStatus $target): self
    {
        return new self("Invalid status transition from {$current->value} to {$target->value}");
    }

    public static function alreadyInStatus(AuditoriumStatus $status): self
    {
        return new self("Auditorium is already in {$status->value} status");
    }
}