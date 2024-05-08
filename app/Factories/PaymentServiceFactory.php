<?php

namespace App\Factories;

use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Exceptions\InvalidOrNonRedirectablePaymentGatewayException;
use App\Services\PaymentGateway\PaymentRedirectInterface;
use App\Services\PaymentGateway\StripeService;

class PaymentServiceFactory
{
    /**
     * @throws InvalidOrNonRedirectablePaymentGatewayException
     */
    public static function create(string $type): PaymentRedirectInterface {
        return match ($type) {
            PaymentGatewayEnum::STRIPE->value => app(StripeService::class),
            default => throw new InvalidOrNonRedirectablePaymentGatewayException()
        };
    }
}
