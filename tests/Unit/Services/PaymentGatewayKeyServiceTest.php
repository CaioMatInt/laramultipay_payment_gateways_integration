<?php

use App\DTOs\PaymentGatewayKey\PaymentGatewayKeyCreationDto;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewayKey;
use App\Services\PaymentGatewayKey\PaymentGatewayKeyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Crypt;
use Tests\Traits\UserTrait;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(UserTrait::class);

describe('PaymentGatewayKeyServiceTest', function () {

    beforeEach(function () {
        $this->mockUser();
        $this->paymentGatewayKeyService = app(PaymentGatewayKeyService::class);
        $this->randomPaymentGateway = PaymentGateway::factory()->create();
        $this->actingAs($this->user);
    });

    test('can create a payment gateway key', function () {
        $dtoData['key'] = 'key';
        $dtoData['type'] = 'type';
        $dtoData['payment_gateway_id'] = $this->randomPaymentGateway->id;
        $paymentGatewayKeyCreationDto = new PaymentGatewayKeyCreationDto($dtoData);

        $paymentGatewayKey = $this->paymentGatewayKeyService->create($paymentGatewayKeyCreationDto);

        expect($paymentGatewayKey->type)->toBe($dtoData['type'])
            ->and($paymentGatewayKey->payment_gateway_id)->toBe((string) $this->randomPaymentGateway->id)
            ->and($paymentGatewayKey->company_id)->toBe($this->user->company_id);

        $this->assertDatabaseHas('payment_gateway_keys', [
            'id' => $paymentGatewayKey->id,
            'type' => $dtoData['type'],
            'payment_gateway_id' => $this->randomPaymentGateway->id,
            'company_id' => $this->user->company_id
        ]);

        $paymentGatewayKey = PaymentGatewayKey::select('key')->find($paymentGatewayKey->id);
        expect(Crypt::decrypt($paymentGatewayKey->key))->toBe($dtoData['key']);
    });

    test('can find a payment gateway key by gateway and company', function () {
        $paymentGatewayKey = PaymentGatewayKey::factory()->create([
            'payment_gateway_id' => $this->randomPaymentGateway->id,
            'company_id' => $this->user->company_id
        ]);

        $foundPaymentGatewayKey = $this->paymentGatewayKeyService->findByGatewayAndCompany(
            $this->randomPaymentGateway->id,
            $this->user->company_id,
            null
        );

        expect($foundPaymentGatewayKey->id)->toBe($paymentGatewayKey->id)
            ->and($foundPaymentGatewayKey->type)->toBe($paymentGatewayKey->type)
            ->and($foundPaymentGatewayKey->payment_gateway_id)->toBe($paymentGatewayKey->payment_gateway_id)
            ->and($foundPaymentGatewayKey->company_id)->toBe($paymentGatewayKey->company_id);
    });

    test('can find a payment gateway key by gateway, company and type', function () {
        $paymentGatewayKey = PaymentGatewayKey::factory()->create([
            'payment_gateway_id' => $this->randomPaymentGateway->id,
            'company_id' => $this->user->company_id,
            'type' => 'type'
        ]);

        $foundPaymentGatewayKey = $this->paymentGatewayKeyService->findByGatewayAndCompany(
            $this->randomPaymentGateway->id,
            $this->user->company_id,
            'type'
        );

        expect($foundPaymentGatewayKey->id)->toBe($paymentGatewayKey->id)
            ->and($foundPaymentGatewayKey->type)->toBe($paymentGatewayKey->type)
            ->and($foundPaymentGatewayKey->payment_gateway_id)->toBe($paymentGatewayKey->payment_gateway_id)
            ->and($foundPaymentGatewayKey->company_id)->toBe($paymentGatewayKey->company_id);
    });

    test('throws an exception when payment gateway key is not found', function () {
        $this->expectException(ModelNotFoundException::class);
        $this->paymentGatewayKeyService->findByGatewayAndCompany(
            $this->randomPaymentGateway->id,
            $this->user->company_id,
            null
        );
    });
});
