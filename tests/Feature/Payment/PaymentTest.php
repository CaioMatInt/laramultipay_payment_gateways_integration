<?php

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Enums\PaymentMethod\PaymentMethodEnum;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\PaymentGenericStatus;
use App\Models\PaymentMethod;
use Carbon\Carbon;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);
uses(\Tests\Traits\PaymentTrait::class);

describe('payments.index', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('can get all payments', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGenericStatus = PaymentGenericStatus::factory()->create(
            [
                'name' => PaymentGenericStatusEnum::PENDING->value
            ]
        );
        $paymentMethod = PaymentMethod::factory()->create(['name' => PaymentMethodEnum::CREDIT_CARD->value]);

        $paymentGateway = PaymentGateway::factory()->create();

        $payments = Payment::factory(5)->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'currency' => PaymentCurrencyEnum::USD->value,
            'payment_generic_status_id' => $paymentGenericStatus->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_gateway_id' => $paymentGateway->id
        ]);

        $response = $this->getJson(route('payment.index'));
        $response->assertOk();

        $response->assertJsonCount(5, 'data');

        foreach ($payments as $payment) {
            $response->assertJsonFragment([
                'uuid' => $payment->uuid,
                'name' => $payment->name,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'payment_generic_status' => PaymentGenericStatusEnum::PENDING->value,
                'payment_method' => PaymentMethodEnum::CREDIT_CARD->value,
                'expires_at' => $payment->expires_at->format('Y-m-d H:i'),
                'payment_gateway' => $paymentGateway->name,
            ]);
        }
    });

    test('cant get all payments without being authenticated', function () {
        $response = $this->getJson(route('payment.index'));
        $response->assertUnauthorized();
    });

    test('should get an empty array if there are no payments', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('payment.index'));
        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    });

    test('can get payments with pagination', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGenericStatus = PaymentGenericStatus::factory()->create(
            [
                'name' => PaymentGenericStatusEnum::PENDING->value
            ]
        );

        $paymentMethod = PaymentMethod::factory()->create(['name' => PaymentMethodEnum::CREDIT_CARD->value]);

        Payment::factory(15)->create([
            'payment_generic_status_id' => $paymentGenericStatus->id,
            'payment_method_id' => $paymentMethod->id,
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('payment.index', ['perPage' => 5]));
        $response->assertOk();
        $response->assertJsonCount(5, 'data');

        $response->assertJsonStructure([
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
        ]);
    });

    test('should return only fifteen payments per page if perPage is not defined', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGenericStatus = PaymentGenericStatus::factory()->create(
            [
                'name' => PaymentGenericStatusEnum::PENDING->value
            ]
        );

        $paymentMethod = PaymentMethod::factory()->create(['name' => PaymentMethodEnum::CREDIT_CARD->value]);

        Payment::factory(16)->create([
            'payment_generic_status_id' => $paymentGenericStatus->id,
            'payment_method_id' => $paymentMethod->id,
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('payment.index'));
        $response->assertOk();
        $response->assertJsonCount(15, 'data');
    });
});

describe('payments.store', function () {

    beforeEach(function () {
        test()->mockUser();
        test()->mockCompanyAdminUser();
    });

    test('can create a payment', function () {
        $this->actingAs($this->userCompanyAdmin);

        PaymentGenericStatus::factory()->create(['name' => PaymentGenericStatusEnum::PENDING->value]);
        PaymentMethod::factory()->create(['name' => PaymentMethodEnum::CREDIT_CARD->value]);

        $stripePaymentGateway = PaymentGateway::factory()->create(['name' => PaymentGatewayEnum::STRIPE->value]);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'currency' => PaymentCurrencyEnum::USD->value,
                'payment_method' => PaymentMethodEnum::CREDIT_CARD->value,
                'payment_gateway' => $stripePaymentGateway->name,
            ]
        );

        $response = $this->postJson(route('payment.store'), $paymentPayload);
        $response->assertCreated();

        $expiresAt = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $paymentPayload['expires_at'])->format('Y-m-d H:i'
        );

        $payment = Payment::first();

        $response->assertJsonFragment([
            'uuid' => $payment->uuid,
            'name' => $paymentPayload['name'],
            'amount' => $paymentPayload['amount'],
            'currency' => $paymentPayload['currency'],
            'payment_generic_status' => PaymentGenericStatusEnum::PENDING->value,
            'payment_method' => PaymentMethodEnum::CREDIT_CARD->value,
            'expires_at' => $expiresAt,
            'payment_gateway' => $stripePaymentGateway->name,
        ]);
    });

    test('cant create a payment without being authenticated', function () {
        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
            ]);

        $response = $this->postJson(route('payment.store'), $paymentPayload);
        $response->assertUnauthorized();
    });

    test('cant create a payment with invalid currency', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'currency' => 'invalid_currency',
            ]);

        $response = $this->postJson(route('payment.store'), $paymentPayload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['currency']);

        $validCurrencies = implode(',', PaymentCurrencyEnum::values());
        $response->assertJsonFragment(['The currency must be one of the following: '.$validCurrencies]);
    });

    test('cant create a payment with invalid amount', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'amount' => 'invalid_amount',
            ]);

        $response = $this->postJson(route('payment.store'), $paymentPayload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);

        $response->assertJsonFragment(['The amount field must be an integer.']);
    });

    test('cant create a payment with invalid payment method', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'payment_method' => 'invalid_payment_method',
            ]);

        $response = $this->postJson(route('payment.store'), $paymentPayload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_method']);

        $validPaymentMethods = implode(',', PaymentMethodEnum::values());
        $response->assertJsonFragment(['The payment method must be one of the following: '.$validPaymentMethods]);
    });
});


//@@TODO: Add missing tests after defining how the crud is gonna be implemented
