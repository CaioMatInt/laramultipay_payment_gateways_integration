<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGatewayRequest;
use App\Http\Resources\PaymentGatewayResource;
use App\Models\PaymentGateway;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentGatewayController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', PaymentGateway::class);

        return PaymentGatewayResource::collection(PaymentGateway::all());
    }

    public function store(PaymentGatewayRequest $request)
    {
        $this->authorize('create', PaymentGateway::class);

        return new PaymentGatewayResource(PaymentGateway::create($request->validated()));
    }

    public function show(PaymentGateway $paymentGateway)
    {
        $this->authorize('view', $paymentGateway);

        return new PaymentGatewayResource($paymentGateway);
    }

    public function update(PaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        $this->authorize('update', $paymentGateway);

        $paymentGateway->update($request->validated());

        return new PaymentGatewayResource($paymentGateway);
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        $this->authorize('delete', $paymentGateway);

        $paymentGateway->delete();

        return response()->json();
    }
}
