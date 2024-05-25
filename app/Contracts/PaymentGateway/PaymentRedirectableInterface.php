<?php

namespace App\Contracts\PaymentGateway;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;

interface PaymentRedirectableInterface
{
    function redirectToPaymentPage(Payment $payment): RedirectResponse;
}
