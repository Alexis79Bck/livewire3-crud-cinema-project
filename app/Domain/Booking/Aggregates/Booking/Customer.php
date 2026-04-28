<?php

namespace App\Domain\Booking\Aggregates\Booking;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa la información del cliente en una reserva.
 */
final class Customer
{
    public function __construct(
        private string $name,
        private string $email,
        private string $phone = ''
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (trim($this->name) === '') {
            throw DomainException::emptyCustomerName();
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw DomainException::invalidEmail($this->email);
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function phone(): string
    {
        return $this->phone;
    }
}
