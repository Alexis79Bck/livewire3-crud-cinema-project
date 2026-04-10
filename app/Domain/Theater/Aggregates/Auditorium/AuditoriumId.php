<?php

/**
 * Value Object que representa el identificador único de un auditorio.
 *
 * Este objeto valor garantiza que los identificadores de auditorio sean UUIDs válidos
 * y proporciona métodos para su manipulación segura.
 *
 * El identificador es inmutable una vez creado.
 */

namespace App\Domain\Theater\Aggregates\Auditorium;


use App\Domain\Theater\Exceptions\InvalidAuditoriumId;
use Ramsey\Uuid\Uuid;

final class AuditoriumId
{
    private string $value;

    private function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw InvalidAuditoriumId::formatInvalid();
        }

        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
