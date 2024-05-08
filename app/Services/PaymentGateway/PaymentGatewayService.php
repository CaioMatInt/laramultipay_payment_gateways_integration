<?php

namespace App\Services\PaymentGateway;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewayService
{

    CONST FULL_MASK_THRESHOLD = 5;
    CONST VISIBLE_CHAT_COUNT = 1;
    CONST TOTAL_VISIBLE_CHARACTERS = 2 * self::VISIBLE_CHAT_COUNT;

    public function __construct(
        private readonly PaymentGateway $model,
    ) { }

    public function findCached(int $id): PaymentGateway
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_gateway.by_id') . $id, function () use ($id) {
            return $this->model->findOrFail($id);
        });
    }

    public function findCachedByName(string $name): PaymentGateway
    {
        //@@TODO: Clear cache via Event/Listener
        return Cache::rememberForever(config('cache_keys.payment_gateway.by_name') . $name, function () use ($name) {
            return $this->model->where('name', $name)->firstOrFail();
        });
    }

    //@@TODO: Missing automated tests/should do some research about how other providers do this
    public function getMaskedKey(string $encryptedKey, string $maskChar = '*'): string
    {
        $decryptedKey = Crypt::decrypt($encryptedKey);
        $keyLength = strlen($decryptedKey);

        if ($keyLength <= self::FULL_MASK_THRESHOLD) {
            return str_repeat($maskChar, $keyLength);
        }

        $visiblePart = self::VISIBLE_CHAT_COUNT;
        $maskedLength = $keyLength - self::TOTAL_VISIBLE_CHARACTERS;

        return substr($decryptedKey, 0, $visiblePart)
            . str_repeat($maskChar, $maskedLength)
            . substr($decryptedKey, -$visiblePart);
    }
}
