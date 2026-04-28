<?php

namespace App\Domain\Shared\Enums;

/**
 * Enum que representa los métodos de pago disponibles.
 *
 * Define las formas en que los clientes pueden pagar sus reservas:
 * - CREDIT_CARD: Tarjeta de crédito/débito
 * - DEBIT_CARD: Tarjeta de débito
 * - PAYPAL: PayPal
 * - CASH: Pago en efectivo (solo para compras presenciales)
 * - BANK_TRANSFER: Transferencia bancaria
 */
enum PaymentMethod: string
{
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case PAYPAL = 'paypal';
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';

    /**
     * Retorna una etiqueta legible para el método de pago.
     */
    public function label(): string
    {
        return match($this) {
            self::CREDIT_CARD => 'Tarjeta de Crédito',
            self::DEBIT_CARD => 'Tarjeta de Débito',
            self::PAYPAL => 'PayPal',
            self::CASH => 'Efectivo',
            self::BANK_TRANSFER => 'Transferencia Bancaria',
        };
    }
}
