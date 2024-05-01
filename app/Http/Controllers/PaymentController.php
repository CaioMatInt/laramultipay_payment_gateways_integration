<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(PaymentRequest $request)
    {
        return new PaymentResource(Payment::create($request->validated()));
    }
}
