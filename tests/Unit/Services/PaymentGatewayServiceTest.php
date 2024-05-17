<?php

use App\Models\PaymentGateway;
use App\Services\PaymentGateway\PaymentGatewayService;
use Illuminate\Support\Facades\Cache;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentGatewayServiceTest', function () {

    beforeEach(function () {
        $this->paymentGatewayService = app(PaymentGatewayService::class);
    });

    test('can find by id', function () {
        $paymentGateway = PaymentGateway::factory()->create();

        $paymentGateway = $this->paymentGatewayService->findCached($paymentGateway->id);

        expect($paymentGateway->id)->toBe($paymentGateway->id)
            ->and($paymentGateway->name)->toBe($paymentGateway->name);
    });

    test('should cache find by id result', function () {
        $paymentGateway = PaymentGateway::factory()->create();

        $this->paymentGatewayService->findCached($paymentGateway->id);

        expect(Cache::has(config('cache_keys.payment_gateway.by_id') . $paymentGateway->id))->toBeTrue();

        $cachedPaymentGateway = Cache::get(config('cache_keys.payment_gateway.by_id') . $paymentGateway->id);
        expect($cachedPaymentGateway->id)->toBe($paymentGateway->id)
            ->and($cachedPaymentGateway->name)->toBe($paymentGateway->name);
    });

    test('can find by name', function () {
        $paymentGateway = PaymentGateway::factory()->create();

        $paymentGateway = $this->paymentGatewayService->findCachedByName($paymentGateway->name);

        expect($paymentGateway->id)->toBe($paymentGateway->id)
            ->and($paymentGateway->name)->toBe($paymentGateway->name);
    });

    test('should cache find by name result', function () {
        $paymentGateway = PaymentGateway::factory()->create();

        $this->paymentGatewayService->findCachedByName($paymentGateway->name);

        expect(Cache::has(config('cache_keys.payment_gateway.by_name') . $paymentGateway->name))->toBeTrue();

        $cachedPaymentGateway = Cache::get(config('cache_keys.payment_gateway.by_name') . $paymentGateway->name);
        expect($cachedPaymentGateway->id)->toBe($paymentGateway->id)
            ->and($cachedPaymentGateway->name)->toBe($paymentGateway->name);
    });
});
