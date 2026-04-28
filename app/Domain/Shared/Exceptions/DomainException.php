<?php

namespace App\Domain\Shared\Exceptions;

use Exception;

/**
 * Clase base abstracta para todas las excepciones del dominio.
 *
 * Esta clase sirve como base para todas las excepciones específicas del dominio,
 * estableciendo un patrón común para el manejo de errores en la capa de dominio.
 *
 * Las excepciones del dominio representan violaciones de las reglas de negocio
 * y deberían ser capturadas y manejadas en la capa de aplicación o en los
 * controladores, nunca en el dominio mismo.
 *
 * Ejemplos de excepciones derivadas:
 * - \App\Domain\Catalog\Exceptions\InvalidMovieId
 * - \App\Domain\Catalog\Exceptions\InvalidMovieTitle
 * - \App\Domain\Catalog\Exceptions\InvalidMovieStatus
 *
 * @extends Exception Clase base de excepciones de PHP
 */
abstract class DomainException extends Exception
{
    public static function emptyAuditoriumId(): self
    {
        return new static('Auditorium ID cannot be empty.');
    }

    public static function emptyAuditoriumName(): self
    {
        return new static('Auditorium name cannot be empty.');
    }

    public static function auditoriumNameTooLong(int $maxLength): self
    {
        return new static(sprintf('Auditorium name cannot exceed %d characters.', $maxLength));
    }

    public static function invalidSeatRow(): self
    {
        return new static('Seat row cannot be empty.');
    }

    public static function invalidSeatNumber(int $number): self
    {
        return new static(sprintf('Seat number must be a positive integer. Given: %d', $number));
    }

    public static function emptyShowtimeId(): self
    {
        return new static('Showtime ID cannot be empty.');
    }

    public static function invalidSchedule(\DateTimeImmutable $start, \DateTimeImmutable $end): self
    {
        return new static(sprintf(
            'Schedule start time (%s) must be before end time (%s).',
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s')
        ));
    }

    public static function invalidMoneyAmount(int $amount): self
    {
        return new static(sprintf('Money amount must be non-negative. Given: %d', $amount));
    }

    public static function invalidCurrency(string $currency): self
    {
        return new static(sprintf('Currency must be a 3-letter ISO code. Given: %s', $currency));
    }

    public static function currencyMismatch(string $expected, string $actual): self
    {
        return new static(sprintf(
            'Currency mismatch. Expected: %s, Actual: %s',
            $expected,
            $actual
        ));
    }

    public static function invalidDateRange(\DateTimeImmutable $start, \DateTimeImmutable $end): self
    {
        return new static(sprintf(
            'Date range invalid: start (%s) must be before end (%s).',
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s')
        ));
    }

    public static function emptyBookingId(): self
    {
        return new static('Booking ID cannot be empty.');
    }

    public static function emptyCustomerName(): self
    {
        return new static('Customer name cannot be empty.');
    }

    public static function invalidEmail(string $email): self
    {
        return new static(sprintf('Invalid email address: %s', $email));
    }
}
