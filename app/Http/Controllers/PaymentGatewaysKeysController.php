<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentGatewaysKeysRequest;
use App\Http\Resources\PaymentGatewaysKeysResource;
use App\Models\PaymentGatewayKey;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentGatewaysKeysController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', PaymentGatewayKey::class);

        return PaymentGatewaysKeysResource::collection(PaymentGatewayKey::all());
    }

    public function store(PaymentGatewaysKeysRequest $request)
    {
        $this->authorize('create', PaymentGatewayKey::class);

        return new PaymentGatewaysKeysResource(PaymentGatewayKey::create($request->validated()));
    }

    public function show(PaymentGatewayKey $paymentGatewaysKeys)
    {
        $this->authorize('view', $paymentGatewaysKeys);

        return new PaymentGatewaysKeysResource($paymentGatewaysKeys);
    }

    public function update(PaymentGatewaysKeysRequest $request, PaymentGatewayKey $paymentGatewaysKeys)
    {
        $this->authorize('update', $paymentGatewaysKeys);

        $paymentGatewaysKeys->update($request->validated());

        return new PaymentGatewaysKeysResource($paymentGatewaysKeys);
    }

    public function destroy(PaymentGatewayKey $paymentGatewaysKeys)
    {
        $this->authorize('delete', $paymentGatewaysKeys);

        $paymentGatewaysKeys->delete();

        return response()->json();
    }
}
