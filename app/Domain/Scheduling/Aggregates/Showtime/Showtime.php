<?php

namespace App\Domain\Scheduling\Aggregates\Showtime;

use App\Domain\Catalog\ValueObjects\MovieId;
use App\Domain\Shared\Events\DomainEvent;
use App\Domain\Scheduling\Aggregates\Showtime\Exceptions\InvalidShowtimeSchedule;
use App\Domain\Scheduling\Events\ShowtimeCancelled;
use App\Domain\Scheduling\Events\ShowtimeStarted;
use App\Domain\Scheduling\Events\ShowtimeUpdated;
use App\Domain\Scheduling\Events\ShowtimeScheduled;
use App\Domain\Scheduling\ValueObjects\Schedule;
use App\Domain\Scheduling\ValueObjects\ShowtimeId;
use App\Domain\Scheduling\ValueObjects\ShowtimeStatus;
use App\Domain\Theater\ValueObjects\AuditoriumId;
use App\Domain\Shared\ValueObjects\Money;

/**
 * Aggregate Root que representa un showtime (función de cine).
 *
 * Encapsula toda la información y comportamiento relacionado con una
 * función específica de una película en un auditorio particular:
 * - Identificador único (ShowtimeId)
 * - Película (MovieId)
 * - Auditorio (AuditoriumId)
 * - Programación (Schedule)
 * - Precio base (Money)
 * - Estado (active/inactive)
 *
 * El aggregate garantiza la consistencia de los datos y encapsula las reglas
 * de negocio como validación de horarios y disponibilidad.
 *
 * Utiliza el patrón de fábrica estático para la creación y el método
 * reconstitute para reconstruir desde datos persistidos.
 *
 * @see ShowtimeStatus Estados posibles del showtime
 */
final class Showtime
{
    /** @var DomainEvent[] */
    private array $events = [];

    private function __construct(
        private ShowtimeId $id,
        private MovieId $movieId,
        private AuditoriumId $auditoriumId,
        private Schedule $schedule,
        private Money $basePrice,
        private ShowtimeStatus $status
    ) {
        // La validación de solapamiento debe hacerse en el repositorio/handler
        // cuando se tienen todos los showtimes existentes para comparar
    }

    /**
     * Crea un nuevo showtime.
     */
    public static function schedule(
        ShowtimeId $id,
        MovieId $movieId,
        AuditoriumId $auditoriumId,
        Schedule $schedule,
        Money $basePrice,
        ShowtimeStatus $status
    ): self {
        self::validateSchedule($schedule);

        if (!$status->isActive()) {
            throw new \DomainException('Cannot schedule a showtime with inactive status.');
        }

        $showtime = new self($id, $movieId, $auditoriumId, $schedule, $basePrice, $status);

        $showtime->recordEvent(new ShowtimeScheduled(
            $id,
            $schedule
        ));

        return $showtime;
    }

    /**
     * Reconstruye un showtime desde datos persistidos.
     */
    public static function reconstitute(
        ShowtimeId $id,
        MovieId $movieId,
        AuditoriumId $auditoriumId,
        Schedule $schedule,
        Money $basePrice,
        ShowtimeStatus $status
    ): self {
        return new self($id, $movieId, $auditoriumId, $schedule, $basePrice, $status);
    }

    /**
     * Actualiza la programación del showtime.
     */
    public function reschedule(Schedule $newSchedule): void
    {
        self::validateSchedule($newSchedule);

        if (!$this->status->isActive()) {
            throw new \DomainException('Cannot reschedule an inactive showtime.');
        }

        if ($this->hasStarted()) {
            throw new \DomainException('Cannot reschedule a showtime that has already started.');
        }

        $oldSchedule = $this->schedule;
        $this->schedule = $newSchedule;

        $this->recordEvent(new ShowtimeUpdated(
            $this->id,
            $oldSchedule,
            $newSchedule
        ));
    }

    /**
     * Cancela el showtime.
     */
    public function cancel(): void
    {
        if (!$this->status->isActive()) {
            throw new \DomainException('Showtime is already inactive.');
        }

        $this->status = ShowtimeStatus::INACTIVE;

        $this->recordEvent(new ShowtimeCancelled($this->id));
    }

    /**
     * Marca el showtime como comenzado.
     */
    public function start(): void
    {
        if (!$this->status->isActive()) {
            throw new \DomainException('Cannot start an inactive showtime.');
        }

        $this->recordEvent(new ShowtimeStarted($this->id));
    }

    /**
     * Verifica si el showtime se solapa con otro showtime en el mismo auditorio.
     */
    public function overlapsWith(Showtime $other): bool
    {
        return $this->isSameAuditorium($other) && $this->schedule->overlapsWith($other->schedule);
    }

    /**
     * Verifica si el showtime es en el mismo auditorio.
     */
    public function isSameAuditorium(Showtime $other): bool
    {
        return $this->auditoriumId->equals($other->auditoriumId);
    }

    /**
     * Verifica si el showtime ya ha comenzado.
     */
    public function hasStarted(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->schedule->startTime() <= $now;
    }

    /**
     * Verifica si el showtime ya finalizó.
     */
    public function hasFinished(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->schedule->endTime() <= $now;
    }

    /**
     * Verifica si el showtime aún no ha comenzado.
     */
    public function isUpcoming(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->schedule->startTime() > $now;
    }

    /**
     * Verifica si el showtime está actualmente en curso.
     */
    public function isRunning(): bool
    {
        $now = new \DateTimeImmutable();
        return $now >= $this->schedule->startTime() && $now <= $this->schedule->endTime();
    }

    /**
     * Verifica si el showtime está activo.
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * Verifica que no haya bookings activos que impidan hacer cambios en el showtime.
     */
    public function canBeModified(): bool
    {
        // Esta validación se hará en el repositorio/application layer
        // donde se consultarán los bookings existentes
        return true;
    }

    private static function validateSchedule(Schedule $schedule): void
    {
        if ($schedule->startTime() >= $schedule->endTime()) {
            throw InvalidShowtimeSchedule::endTimeBeforeStartTime();
        }
    }

    /**
     * Retorna el ID del showtime.
     */
    public function id(): ShowtimeId
    {
        return $this->id;
    }

    /**
     * Retorna el ID de la película.
     */
    public function movieId(): MovieId
    {
        return $this->movieId;
    }

    /**
     * Retorna el ID del auditorio.
     */
    public function auditoriumId(): AuditoriumId
    {
        return $this->auditoriumId;
    }

    /**
     * Retorna el schedule del showtime.
     */
    public function schedule(): Schedule
    {
        return $this->schedule;
    }

    /**
     * Retorna el precio base del showtime.
     */
    public function basePrice(): Money
    {
        return $this->basePrice;
    }

    /**
     * Retorna el estado del showtime.
     */
    public function status(): ShowtimeStatus
    {
        return $this->status;
    }

    /**
     * Retorna la fecha/hora de inicio.
     */
    public function startTime(): \DateTimeImmutable
    {
        return $this->schedule->startTime();
    }

    /**
     * Retorna la fecha/hora de fin.
     */
    public function endTime(): \DateTimeImmutable
    {
        return $this->schedule->endTime();
    }

    /**
     * Retorna los eventos de dominio registrados.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    /**
     * Retorna los eventos de dominio registrados sin limpiarlos.
     *
     * @return DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Limpia todos los eventos registrados.
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }

    /**
     * Registra un evento de dominio.
     */
    protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
