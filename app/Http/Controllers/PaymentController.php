<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return PaymentResource::collection(Payment::all());
    }

    public function store(PaymentRequest $request)
    {
        return new PaymentResource(Payment::create($request->validated()));
    }

    public function show(Payment $payment)
    {
        return new PaymentResource($payment);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return new PaymentResource($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json();
    }
}
