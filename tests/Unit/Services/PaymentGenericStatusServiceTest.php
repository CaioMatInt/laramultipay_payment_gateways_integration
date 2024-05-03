<?php

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

        expect(Cache::has('payment_generic_status_' . $paymentGenericStatus->id))->toBeTrue();

        $cachedPaymentGenericStatus = Cache::get('payment_generic_status_' . $paymentGenericStatus->id);
        expect($cachedPaymentGenericStatus->id)->toBe($paymentGenericStatus->id)
            ->and($cachedPaymentGenericStatus->name)->toBe($paymentGenericStatus->name);
    });
});
