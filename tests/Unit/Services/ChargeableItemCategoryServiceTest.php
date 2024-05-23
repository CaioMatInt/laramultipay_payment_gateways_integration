<?php

use App\Services\PaymentGateway\PaymentGatewayService;
use Tests\Traits\UserTrait;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(UserTrait::class);

describe('ChargeableItemCategoryServiceTest', function () {

    beforeEach(function () {
        $this->paymentGatewayService = app(PaymentGatewayService::class);
    });

});
