<?php

namespace Botble\Payment\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static PaymentMethodEnum STRIPE()
 * @method static PaymentMethodEnum PAYPAL()
 * @method static PaymentMethodEnum COD()
 * @method static PaymentMethodEnum BANK_TRANSFER()
 */
class PaymentMethodEnum extends Enum
{
    public const STRIPE = 'stripe';
    public const PAYPAL = 'paypal';
    public const COD = 'cod';
    public const BANK_TRANSFER = 'bank_transfer';

    /**
     * @var string
     */
    public static $langPath = 'plugins/payment::payment.methods';
}
