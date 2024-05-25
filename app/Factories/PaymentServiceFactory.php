<?php

namespace App\Factories;

use App\Contracts\PaymentGateway\PaymentRedirectableInterface;
use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Exceptions\PaymentGateway\InvalidOrNonRedirectablePaymentGatewayException;
use App\Services\PaymentGateway\StripeService;

class PaymentServiceFactory
{
    /**
     * @throws InvalidOrNonRedirectablePaymentGatewayException
     */
    public static function create(string $type): PaymentRedirectableInterface {
        return match ($type) {
            PaymentGatewayEnum::STRIPE->value => app(StripeService::class),
            default => throw new InvalidOrNonRedirectablePaymentGatewayException()
        };
    }
}
