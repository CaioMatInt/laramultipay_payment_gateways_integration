<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentGateway\PaymentGatewayServiceInterface;
use App\Contracts\PaymentGateway\PaymentRedirectableInterface;
use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Enums\PaymentGatewayKey\PaymentGatewayKeyTypes;
use App\Models\Payment;
use App\Services\PaymentGatewayKey\PaymentGatewayKeyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService implements PaymentGatewayServiceInterface, PaymentRedirectableInterface
{
    CONST STRIPE_KEY_TYPE = PaymentGatewayKeyTypes::SECRET_KEY->value;
    CONST SUCCESS_URL = '/api/transactions/success?session_id={CHECKOUT_SESSION_ID}';

    public function __construct(
        private readonly PaymentGatewayKeyService $paymentGatewayKeyService,
        private readonly PaymentGatewayService $paymentGatewayService
    ) { }

    /**
     * @param Payment $payment
     * @return RedirectResponse
     */
    public function redirectToPaymentPage(Payment $payment): RedirectResponse
    {
        $paymentGateway = $this->paymentGatewayService->findCachedByName(PaymentGatewayEnum::STRIPE->value);

        $secretKey = $this->paymentGatewayKeyService->findByGatewayAndCompany(
            $paymentGateway->id,
            $payment->company_id,
            self::STRIPE_KEY_TYPE
        );

        Stripe::setApiKey(Crypt::decrypt($secretKey->key));

        $session = Session::create($this->getSessionBody($payment));

        return redirect()->away($session->url);
    }

    /**
     * @param Payment $payment
     * @return array
     */
    private function getSessionBody(Payment $payment): array
    {
        return [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $payment->currency,
                    'product_data' => [
                        'name' => $payment->name,
                    ],
                    'unit_amount' => $payment->amount,
                ],
                //@@TODO: Implement this
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url(self::SUCCESS_URL),
            'expires_at' => $payment->expires_at->getTimestamp(),
        ];
    }
}
