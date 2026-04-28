<?php

/**
 * Excepción que se lanza cuando un auditorio no se encuentra en el sistema.
 *
 * Esta excepción se utiliza en el Repository cuando se intenta buscar un auditorio
 * por su ID y no se encuentra ningún registro coincidente.
 *
 * @see \App\Domain\Theater\Repositories\AuditoriumRepository
 * @see \App\Domain\Theater\Aggregates\Auditorium\AuditoriumId
 * @see \App\Domain\Shared\Exceptions\DomainException Clase base de excepciones del dominio
 */

namespace App\Domain\Theater\Exceptions;

use App\Domain\Shared\Exceptions\DomainException;
use App\Domain\Theater\Aggregates\Auditorium\AuditoriumId;

final class AuditoriumNotFoundException extends DomainException
{
    public static function withId(AuditoriumId $id): self
    {
        return new self("Auditorium with ID {$id} was not found.");
    }
}