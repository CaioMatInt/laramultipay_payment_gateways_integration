<?php

namespace App\Services\PaymentGatewayKey;

use App\DTOs\PaymentGatewayKey\PaymentGatewayKeyCreationDto;
use App\Models\PaymentGatewayKey;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayKeyService
{
    public function __construct(
        private readonly PaymentGatewayKey $model
    ) { }

    public function create(PaymentGatewayKeyCreationDto $dto): PaymentGatewayKey
    {
        $data['key'] = Crypt::encrypt($dto->key);
        $data['type'] = $dto->type;
        $data['payment_gateway_id'] = $dto->paymentGatewayId;
        $data['company_id'] = auth()->user()->company_id;

        return $this->model->create($data);
    }

    public function findByGatewayAndCompany(int $paymentGatewayId, int $companyId, ?string $type): PaymentGatewayKey
    {
        $paymentGatewayBuilder = $this->model->where('payment_gateway_id', $paymentGatewayId)
            ->where('company_id', $companyId);

        if ($type) {
            $paymentGatewayBuilder->where('type', $type);
        }

        return $paymentGatewayBuilder->firstOrFail();
    }
}
