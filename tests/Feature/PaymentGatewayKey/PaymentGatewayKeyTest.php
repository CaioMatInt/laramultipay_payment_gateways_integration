<?php

use App\Models\PaymentGateway;
use App\Models\PaymentGatewayKey;
use Illuminate\Support\Facades\Crypt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);

describe('payment-gateway-key.index', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->getJson(route('payment-gateway-key.index'));

        $response->assertUnauthorized();
    });

    test('should return all payment gateway keys related to the users company', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGateway = PaymentGateway::factory()->create();

        $factoryPaymentGatewayKeys = PaymentGatewayKey::factory(3)->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'payment_gateway_id' => $paymentGateway->id,
            'key' => Crypt::encrypt('12345')
        ]);

        $response = $this->getJson(route('payment-gateway-key.index'));

        $response->assertOk();

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'key',
                    'type',
                    'payment_gateway',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $responseData = $response->json()['data'];

        foreach ($responseData as $key => $paymentGatewayKey) {
           expect($paymentGatewayKey['id'])->toBe($factoryPaymentGatewayKeys[$key]->id)
               ->and($paymentGatewayKey['type'])->toBe($factoryPaymentGatewayKeys[$key]->type)
                ->and($paymentGatewayKey['key'])->toBe('*****')
               ->and($paymentGatewayKey['payment_gateway'])->toBe($paymentGateway->name)
               ->and($paymentGatewayKey['created_at'])->toBe(
                   $factoryPaymentGatewayKeys[$key]->created_at->format('Y-m-d\TH:i:s.u\Z')
               )
               ->and($paymentGatewayKey['updated_at'])->toBe(
                   $factoryPaymentGatewayKeys[$key]->updated_at->format('Y-m-d\TH:i:s.u\Z')
               );
        }
    });

    test('should return all payment keys partially masked when key length is greater than 5', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGateway = PaymentGateway::factory()->create();

        PaymentGatewayKey::factory(2)->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'payment_gateway_id' => $paymentGateway->id,
            'key' => Crypt::encrypt('1234567890')
        ]);

        $response = $this->getJson(route('payment-gateway-key.index'));

        $responseData = $response->json()['data'];

        foreach ($responseData as $paymentGatewayKey) {
            expect($paymentGatewayKey['key'])->toBe('1********0');
        }
    });

    test('should return empty data array if there are no payment gateway keys', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('payment-gateway-key.index'));

        $response->assertOk();

        $response->assertJsonCount(0, 'data');
    });
});

describe('payment-gateway-key.store', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->postJson(route('payment-gateway-key.store'));

        $response->assertUnauthorized();
    });

    test('should return a validation error if key is not provided', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('payment-gateway-key.store'));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['key']);

        expect($response->json('errors.key.0'))->toBe('The key field is required.');
    });

    test('should return a validation error if payment gateway id is not provided', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('payment-gateway-key.store'), [
            'key' => '12345'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_gateway_id']);

        expect($response->json('errors.payment_gateway_id.0'))->toBe('The payment gateway id field is required.');
    });

    test('should return a validation error if payment gateway id does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('payment-gateway-key.store'), [
            'payment_gateway_id' => 1
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_gateway_id']);

        expect($response->json('errors.payment_gateway_id.0'))->toBe('The selected payment gateway id is invalid.');
    });

    test('should return a validation error if payment gateway id is not an integer', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('payment-gateway-key.store'), [
            'payment_gateway_id' => 'string'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_gateway_id']);

        expect($response->json('errors.payment_gateway_id.0'))->toBe('The payment gateway id field must be an integer.');
    });

    test('should be able to create a payment gateway key', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGateway = PaymentGateway::factory()->create();
        $paymentGatewayKey = PaymentGatewayKey::factory()->make();
        $key = '12345';

        $response = $this->postJson(route('payment-gateway-key.store'), [
            'key' => $key,
            'type' => $paymentGatewayKey->type,
            'payment_gateway_id' => $paymentGateway->id
        ]);

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'key',
                'type',
                'payment_gateway',
                'created_at',
                'updated_at'
            ]
        ]);

        $responseData = $response->json('data');

        expect($responseData['key'])->toBe('*****')
            ->and($responseData['type'])->toBe($paymentGatewayKey->type)
            ->and($responseData['payment_gateway'])->toBe($paymentGateway->name)
            ->and($responseData['created_at'])->toBe($responseData['created_at'])
            ->and($responseData['updated_at'])->toBe($responseData['updated_at']);

        $paymentGatewayKey = PaymentGatewayKey::first();

        expect(Crypt::decrypt($paymentGatewayKey->key))->toBe($key)
            ->and($paymentGatewayKey->type)->toBe($paymentGatewayKey->type)
            ->and($paymentGatewayKey->payment_gateway_id)->toBe($paymentGateway->id)
            ->and($paymentGatewayKey->company_id)->toBe($this->userCompanyAdmin->company_id);
    });

    test('should be able to create a payment gateway key with a null type', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentGateway = PaymentGateway::factory()->create();
        $key = '12345';

        $response = $this->postJson(route('payment-gateway-key.store'), [
            'key' => $key,
            'payment_gateway_id' => $paymentGateway->id
        ]);

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'key',
                'type',
                'payment_gateway',
                'created_at',
                'updated_at'
            ]
        ]);

        $responseData = $response->json('data');

        expect($responseData['key'])->toBe('*****')
            ->and($responseData['type'])->toBeNull()
            ->and($responseData['payment_gateway'])->toBe($paymentGateway->name)
            ->and($responseData['created_at'])->toBe($responseData['created_at'])
            ->and($responseData['updated_at'])->toBe($responseData['updated_at']);

        $paymentGatewayKey = PaymentGatewayKey::first();

        expect(Crypt::decrypt($paymentGatewayKey->key))->toBe($key)
            ->and($paymentGatewayKey->type)->toBeNull()
            ->and($paymentGatewayKey->payment_gateway_id)->toBe($paymentGateway->id)
            ->and($paymentGatewayKey->company_id)->toBe($this->userCompanyAdmin->company_id);
    });
});
