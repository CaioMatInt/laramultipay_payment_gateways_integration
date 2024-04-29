<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGatewayTransactionStatusesRequest;
use App\Http\Resources\PaymentGatewayTransactionStatusesResource;
use App\Models\PaymentGatewayTransactionStatus;

class PaymentGatewayTransactionStatusesController extends Controller
{
    public function index()
    {
        return PaymentGatewayTransactionStatusesResource::collection(PaymentGatewayTransactionStatus::all());
    }

    public function store(PaymentGatewayTransactionStatusesRequest $request)
    {
        return new PaymentGatewayTransactionStatusesResource(PaymentGatewayTransactionStatus::create($request->validated()));
    }

    public function show(PaymentGatewayTransactionStatus $paymentGatewayTransactionStatuses)
    {
        return new PaymentGatewayTransactionStatusesResource($paymentGatewayTransactionStatuses);
    }

    public function update(PaymentGatewayTransactionStatusesRequest $request, PaymentGatewayTransactionStatus $paymentGatewayTransactionStatuses)
    {
        $paymentGatewayTransactionStatuses->update($request->validated());

        return new PaymentGatewayTransactionStatusesResource($paymentGatewayTransactionStatuses);
    }

    public function destroy(PaymentGatewayTransactionStatus $paymentGatewayTransactionStatuses)
    {
        $paymentGatewayTransactionStatuses->delete();

        return response()->json();
    }
}
