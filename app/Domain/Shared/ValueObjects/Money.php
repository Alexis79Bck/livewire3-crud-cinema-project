<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\DomainException;

/**
 * Value Object que representa una cantidad monetaria.
 *
 * Encapsula un monto y una moneda, asegurando que:
 * - El monto sea positivo
 * - La moneda sea un código de 3 letras (ISO 4217)
 *
 * Esta clase es inmutable: una vez creada, su valor no puede cambiar.
 * Soporta operaciones aritméticas básicas manteniendo la inmutabilidad.
 */
final class Money
{
    public function __construct(
        private int $amount,      // Cantidad en centavos (entero)
        private string $currency  // Código de moneda (p.ej. USD, EUR)
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->amount < 0) {
            throw DomainException::invalidMoneyAmount($this->amount);
        }

        if (!preg_match('/^[A-Z]{3}$/', $this->currency)) {
            throw DomainException::invalidCurrency($this->currency);
        }
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function amountDecimal(): float
    {
        return $this->amount / 100;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        $result = $this->amount - $other->amount;
        return new self($result, $this->currency);
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function isGreaterThan(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->amount > $other->amount;
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw DomainException::currencyMismatch($this->currency, $other->currency);
        }
    }

    public static function fromDecimal(float $amount, string $currency): self
    {
        return new self((int) round($amount * 100), $currency);
    }

    public function __toString(): string
    {
        return sprintf('%s %s', number_format($this->amountDecimal(), 2), $this->currency);
    }
}
