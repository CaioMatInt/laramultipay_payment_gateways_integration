<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentLogsRequest;
use App\Http\Resources\PaymentLogsResource;
use App\Models\PaymentLog;

class PaymentLogsController extends Controller
{
    public function index()
    {
        return PaymentLogsResource::collection(PaymentLog::all());
    }

    public function store(PaymentLogsRequest $request)
    {
        return new PaymentLogsResource(PaymentLog::create($request->validated()));
    }

    public function show(PaymentLog $paymentLogs)
    {
        return new PaymentLogsResource($paymentLogs);
    }

    public function update(PaymentLogsRequest $request, PaymentLog $paymentLogs)
    {
        $paymentLogs->update($request->validated());

        return new PaymentLogsResource($paymentLogs);
    }

    public function destroy(PaymentLog $paymentLogs)
    {
        $paymentLogs->delete();

        return response()->json();
    }
}
