<?php

namespace App\Services\PaymentGateway;

use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Enums\PaymentGatewayKey\PaymentGatewayKeyTypes;
use App\Models\Payment;
use App\Services\PaymentGatewayKey\PaymentGatewayKeyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService implements PaymentGatewayServiceInterface, PaymentRedirectInterface
{
    CONST STRIPE_KEY_TYPE = PaymentGatewayKeyTypes::SECRET_KEY->value;
    CONST SUCCESS_URL = '/api/transactions/success?session_id={CHECKOUT_SESSION_ID}';

    public function __construct(
        private readonly PaymentGatewayKeyService $paymentGatewayKeyService,
        private readonly PaymentGatewayService $paymentGatewayService
    ) { }

    public function redirectToPaymentPage(Payment $payment): RedirectResponse
    {
        $paymentGateway = $this->paymentGatewayService->findCachedByName(PaymentGatewayEnum::STRIPE->value);

        $secretKey = $this->paymentGatewayKeyService->findByGatewayAndCompany(
            $paymentGateway->id,
            $payment->company_id,
            self::STRIPE_KEY_TYPE
        );

        Stripe::setApiKey(Crypt::decrypt($secretKey->key));

        $session = Session::create([
            //@@TODO: Change accordingly to the defined payment method, if set
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $payment->currency,
                    'product_data' => [
                        'name' => $payment->name,
                    ],
                    'unit_amount' => $payment->amount,
                ],
                //@@TODO: Check if I'm gonna be implementing this
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url(self::SUCCESS_URL),
            'expires_at' => $payment->expires_at->getTimestamp(),
        ]);

        return redirect()->away($session->url);
    }
}
