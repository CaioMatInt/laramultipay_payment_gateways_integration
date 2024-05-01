<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);
uses(\Tests\Traits\PaymentTrait::class);

describe('payments', function () {

    beforeEach(function () {
        test()->mockUser();
        test()->mockCompanyAdminUser();
    });

    test('can create a payment', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
            ]);

        $response = $this->post(route('payment.create'), $paymentPayload);
        $response->assertCreated();
    });

    test('cant create a payment without being authenticated', function () {
        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
            ]);

        $response = $this->post(route('payment.create'), $paymentPayload);
        $response->assertUnauthorized();
    });

    test('cant create a payment with invalid currency', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'currency' => 'invalid_currency',
            ]);

        $response = $this->post(route('payment.create'), $paymentPayload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['currency']);
        //@@todo assert invalid currency message
    });

    test('cant create a payment with invalid payment gateway', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'payment_gateway' => 'invalid_payment_gateway',
            ]);

        $response = $this->post(route('payment.create'), $paymentPayload);
        $response->assertStatus(422);
        //@@todo assert invalid currency message
    });

    test('cant create a payment with invalid amount', function () {
        $this->actingAs($this->userCompanyAdmin);

        $paymentPayload = $this->getCreatePaymentPayload(
            [
                'user_id' => $this->userCompanyAdmin->id,
                'amount' => 'invalid_amount',
            ]);

        $response = $this->post(route('payment.create'), $paymentPayload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['amount']);
        //@@todo assert invalid currency message
    });

});
