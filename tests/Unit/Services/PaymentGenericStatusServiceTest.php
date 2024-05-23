<?php

use App\Contracts\ModelAware;
use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Models\PaymentGenericStatus;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use Illuminate\Support\Facades\Cache;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentGenericStatusService', function () {

    beforeEach(function () {
        $this->paymentGenericStatusService = app(PaymentGenericStatusService::class);
    });

    test('can find cached by id', function () {
        $paymentGenericStatus = PaymentGenericStatus::factory()->create();

        $paymentGenericStatus = $this->paymentGenericStatusService->findCached($paymentGenericStatus->id);

        expect($paymentGenericStatus->id)->toBe($paymentGenericStatus->id)
            ->and($paymentGenericStatus->name)->toBe($paymentGenericStatus->name);
    });

    test('should cache find by id result', function () {
        $paymentGenericStatus = PaymentGenericStatus::factory()->create();

        $this->paymentGenericStatusService->findCached($paymentGenericStatus->id);

        expect(Cache::has(config('cache_keys.payment_generic_status.by_id') . $paymentGenericStatus->id))
            ->toBeTrue();

        $cachedPaymentGenericStatus = Cache::get(config('cache_keys.payment_generic_status.by_id') . $paymentGenericStatus->id);
        expect($cachedPaymentGenericStatus->id)->toBe($paymentGenericStatus->id)
            ->and($cachedPaymentGenericStatus->name)->toBe($paymentGenericStatus->name);
    });

    test('can get cached initial status', function () {
        PaymentGenericStatus::factory()->create([
            'name' => PaymentGenericStatusEnum::PENDING->value
        ]);

        $initialStatus = $this->paymentGenericStatusService->getCachedInitialStatus();

        expect($initialStatus->name)->toBe(PaymentGenericStatusEnum::PENDING->value);
    });

    test('should cache initial status result', function () {
        PaymentGenericStatus::factory()->create([
            'name' => PaymentGenericStatusEnum::PENDING->value
        ]);

        $this->paymentGenericStatusService->getCachedInitialStatus();

        expect(Cache::has(config('cache_keys.payment_generic_status.initial')))
            ->toBeTrue();

        $cachedInitialStatus = Cache::get(config('cache_keys.payment_generic_status.initial'));
        expect($cachedInitialStatus->name)->toBe(PaymentGenericStatusEnum::PENDING->value);
    });

    test('ensure that PaymentGenericStatusService implements ModelAware', function () {
        expect($this->paymentGenericStatusService)->toBeInstanceOf(ModelAware::class);
    });
});
