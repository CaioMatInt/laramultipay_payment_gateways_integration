<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionLogsRequest;
use App\Http\Resources\TransactionLogsResource;
use App\Models\TransactionLogs;

class TransactionLogsController extends Controller
{
    public function index()
    {
        return TransactionLogsResource::collection(TransactionLogs::all());
    }

    public function store(TransactionLogsRequest $request)
    {
        return new TransactionLogsResource(TransactionLogs::create($request->validated()));
    }

    public function show(TransactionLogs $transactionLogs)
    {
        return new TransactionLogsResource($transactionLogs);
    }

    public function update(TransactionLogsRequest $request, TransactionLogs $transactionLogs)
    {
        $transactionLogs->update($request->validated());

        return new TransactionLogsResource($transactionLogs);
    }

    public function destroy(TransactionLogs $transactionLogs)
    {
        $transactionLogs->delete();

        return response()->json();
    }
}
