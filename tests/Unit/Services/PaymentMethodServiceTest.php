<?php

use App\Models\PaymentMethod;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Support\Facades\Cache;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentMethodServiceTest', function () {

    beforeEach(function () {
        $this->paymentMethodService = app(PaymentMethodService::class);
    });

    test('can find by name', function () {
        $paymentMethod = PaymentMethod::factory()->create();

        $paymentMethod = $this->paymentMethodService->findCachedByName($paymentMethod->name);

        expect($paymentMethod->id)->toBe($paymentMethod->id)
            ->and($paymentMethod->name)->toBe($paymentMethod->name);
    });

    test('should cache find by name result', function () {
        $paymentMethod = PaymentMethod::factory()->create();

        $this->paymentMethodService->findCachedByName($paymentMethod->name);

        expect(Cache::has('payment_method_' . $paymentMethod->name))->toBeTrue();

        $cachedPaymentMethod = Cache::get('payment_method_' . $paymentMethod->name);
        expect($cachedPaymentMethod->id)->toBe($paymentMethod->id)
            ->and($cachedPaymentMethod->name)->toBe($paymentMethod->name);
    });

    test('can find by id', function () {
        $paymentMethod = PaymentMethod::factory()->create();

        $paymentMethod = $this->paymentMethodService->findCached($paymentMethod->id);

        expect($paymentMethod->id)->toBe($paymentMethod->id)
            ->and($paymentMethod->name)->toBe($paymentMethod->name);
    });

    test('should cache find by id result', function () {
        $paymentMethod = PaymentMethod::factory()->create();

        $this->paymentMethodService->findCached($paymentMethod->id);

        expect(Cache::has('payment_method_' . $paymentMethod->id))->toBeTrue();

        $cachedPaymentMethod = Cache::get('payment_method_' . $paymentMethod->id);
        expect($cachedPaymentMethod->id)->toBe($paymentMethod->id)
            ->and($cachedPaymentMethod->name)->toBe($paymentMethod->name);
    });
});
