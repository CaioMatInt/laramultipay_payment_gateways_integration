<?php

namespace App\Services\PaymentGatewayKey;

use App\DTOs\PaymentGatewayKey\PaymentGatewayKeyCreationDto;
use App\Models\PaymentGatewayKey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayKeyService
{
    CONST FULL_MASK_THRESHOLD = 5;
    CONST VISIBLE_CHAR_COUNT = 1;
    CONST TOTAL_VISIBLE_CHARACTERS = 2 * self::VISIBLE_CHAR_COUNT;

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

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getMaskedKey(string $encryptedKey, string $maskChar = '*'): string
    {
        $decryptedKey = Crypt::decrypt($encryptedKey);
        $keyLength = strlen($decryptedKey);

        if ($keyLength <= self::FULL_MASK_THRESHOLD) {
            return str_repeat($maskChar, $keyLength);
        }

        $visiblePart = self::VISIBLE_CHAR_COUNT;
        $maskedLength = $keyLength - self::TOTAL_VISIBLE_CHARACTERS;

        return substr($decryptedKey, 0, $visiblePart)
            . str_repeat($maskChar, $maskedLength)
            . substr($decryptedKey, -$visiblePart);
    }
}
