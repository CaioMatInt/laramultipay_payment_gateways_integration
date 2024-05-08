<?php

use App\DTOs\Payment\PaymentCreationDto;
use App\Enums\Payment\PaymentCurrencyEnum;
use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Enums\PaymentMethod\PaymentMethodEnum;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\PaymentGenericStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\PaymentGateway\PaymentGatewayService;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentService', function () {

    beforeEach(function () {
        $this->paymentService = app(PaymentService::class);

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->pendingPaymentStatus = PaymentGenericStatus::factory()->create([
            'name' => PaymentGenericStatusEnum::PENDING,
        ]);

        $this->creditCardPaymentMethod = PaymentMethod::factory()->create([
            'name' => PaymentMethodEnum::CREDIT_CARD->value,
        ]);

        $this->paymentGateway = PaymentGateway::factory()->create();
    });

    test('can create a payment', function () {
        $paymentGenericStatusService = Mockery::mock(PaymentGenericStatusService::class);
        $paymentGenericStatusService->shouldReceive('getCachedInitialStatus')
            ->andReturn($this->pendingPaymentStatus);

        $paymentMethodService = Mockery::mock(PaymentMethodService::class);
        $paymentMethodService->shouldReceive('findCachedByName')->andReturn($this->creditCardPaymentMethod);

        $paymentGatewayService = Mockery::mock(PaymentGatewayService::class);
        $paymentGatewayService->shouldReceive('findCachedByName')->andReturn($this->paymentGateway);

        $paymentService = new PaymentService(
            new Payment(),
            $paymentGenericStatusService,
            $paymentMethodService,
            $paymentGatewayService
        );

        $paymentData = Payment::factory()->make([
            'payment_generic_status_id' => $this->pendingPaymentStatus->id,
            'payment_method_id' => $this->creditCardPaymentMethod->id,
            'payment_gateway_id' => $this->paymentGateway->id,
        ]);

        $paymentCreationDto = new PaymentCreationDto([
            'name' => $paymentData->name,
            'payment_method' => PaymentMethodEnum::CREDIT_CARD->value,
            'payment_gateway' => $this->paymentGateway->name,
            'amount' => $paymentData->amount,
            'currency' => $paymentData->currency,
            'expires_at' => $paymentData->expires_at,
        ]);

        $payment = $paymentService->create($paymentCreationDto);

        expect($payment->amount)->toBe($paymentData->amount)
            ->and($payment->name)->toBe($paymentData->name)
            ->and($payment->user_id)->toBe($this->user->id)
            ->and($payment->company_id)->toBe($this->user->company_id)
            ->and($payment->currency)->toBe($paymentData->currency)
            ->and($payment->payment_generic_status_id)->toBe($this->pendingPaymentStatus->id)
            ->and($payment->payment_method_id)->toBe($this->creditCardPaymentMethod->id)
            ->and($payment->payment_gateway_id)->toBe($this->paymentGateway->id)
            ->and($payment->expires_at->toIso8601String())->toBe($paymentData->expires_at->toIso8601String());

        $this->assertDatabaseHas('payments', [
            'amount' => $paymentData->amount,
            'name' => $paymentData->name,
            'user_id' => $this->user->id,
            'company_id' => $this->user->company_id,
            'currency' => $paymentData->currency,
            'payment_generic_status_id' => $this->pendingPaymentStatus->id,
            'payment_method_id' => $this->creditCardPaymentMethod->id,
            'payment_gateway_id' => $this->paymentGateway->id,
            'expires_at' => $paymentData->expires_at->format('Y-m-d H:i:s'),
        ]);
    });

    test('can get paginated payments by company id', function () {
        $currency = PaymentCurrencyEnum::USD->value;

        $payments = Payment::factory(5)->create([
            'company_id' => $this->user->company_id,
            'currency' => $currency,
            'payment_method_id' => $this->creditCardPaymentMethod->id,
            'payment_generic_status_id' => $this->pendingPaymentStatus->id,
        ]);

        $foundPayments = $this->paymentService->getPaginatedByCompanyId($this->user->company_id);

        expect($foundPayments->count())->toBe(5);

        foreach ($foundPayments->toArray()['data'] as $key => $payment) {
            expect($payment['uuid'])->toBe($payments[$key]->uuid)
                ->and($payment['company_id'])->toBe($this->user->company_id)
                ->and($payment['currency'])->toBe($currency)
                ->and($payment['payment_method_id'])->toBe($this->creditCardPaymentMethod->id)
                ->and($payment['payment_generic_status_id'])->toBe($this->pendingPaymentStatus->id);
        }
    });

    test('should return a maximum of 15 payments per company ID by default', function () {
        $currency = PaymentCurrencyEnum::USD->value;

        Payment::factory(20)->create([
            'company_id' => $this->user->company_id,
            'currency' => $currency,
            'payment_method_id' => $this->creditCardPaymentMethod->id,
            'payment_generic_status_id' => $this->pendingPaymentStatus->id
        ]);

        $foundPayments = $this->paymentService->getPaginatedByCompanyId($this->user->company_id);

        expect($foundPayments->count())->toBe(15);
    });
});
