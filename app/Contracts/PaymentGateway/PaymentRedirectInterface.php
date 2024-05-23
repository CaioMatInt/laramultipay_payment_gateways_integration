<?php

namespace App\Contracts\PaymentGateway;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;

interface PaymentRedirectInterface
{
    function redirectToPaymentPage(Payment $payment): RedirectResponse;
}
