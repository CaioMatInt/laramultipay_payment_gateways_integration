<?php

namespace App\Services\PaymentGateway;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;

interface PaymentRedirectInterface
{
    public function redirectToPaymentPage(Payment $payment): RedirectResponse;
}
