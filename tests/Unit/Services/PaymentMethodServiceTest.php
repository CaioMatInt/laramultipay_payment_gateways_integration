<?php

use App\Contracts\ModelAware;
use App\Models\PaymentMethod;
use App\Services\PaymentMethod\PaymentMethodService;
use Illuminate\Support\Facades\Cache;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentMethodServiceTest', function () {

    beforeEach(function () {
        $this->randomPaymentMethodService = app(PaymentMethodService::class);
        $this->randomPaymentMethod = PaymentMethod::factory()->create();
    });

    test('can find by name', function () {
       $paymentMethod = $this->randomPaymentMethodService->findCachedByName($this->randomPaymentMethod->name);

        expect($paymentMethod->id)->toBe($paymentMethod->id)
            ->and($paymentMethod->name)->toBe($paymentMethod->name);
    });

    test('should cache find by name result', function () {
        $this->randomPaymentMethodService->findCachedByName($this->randomPaymentMethod->name);

        expect(Cache::has(config('cache_keys.payment_method.by_name') . $this->randomPaymentMethod->name))->toBeTrue();

        $cachedPaymentMethod = Cache::get(config('cache_keys.payment_method.by_name') . $this->randomPaymentMethod->name);

        expect($cachedPaymentMethod->id)->toBe($this->randomPaymentMethod->id)
            ->and($cachedPaymentMethod->name)->toBe($this->randomPaymentMethod->name);
    });

    test('can find by id', function () {
        $paymentMethod = $this->randomPaymentMethodService->findCached($this->randomPaymentMethod->id);

        expect($paymentMethod->id)->toBe($paymentMethod->id)
            ->and($paymentMethod->name)->toBe($paymentMethod->name);
    });

    test('should cache find by id result', function () {
        $this->randomPaymentMethodService->findCached($this->randomPaymentMethod->id);

        expect(Cache::has(config('cache_keys.payment_method.by_id')
            . $this->randomPaymentMethod->id
        ))->toBeTrue();

        $cachedPaymentMethod = Cache::get(
            config('cache_keys.payment_method.by_id')
            . $this->randomPaymentMethod->id
        );

        expect($cachedPaymentMethod->id)->toBe($this->randomPaymentMethod->id)
            ->and($cachedPaymentMethod->name)->toBe($this->randomPaymentMethod->name);
    });

    test('ensure that PaymentMethodService implements ModelAware', function () {
        expect($this->randomPaymentMethodService)->toBeInstanceOf(ModelAware::class);
    });
});
