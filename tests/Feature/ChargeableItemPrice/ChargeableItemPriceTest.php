<?php

use App\Models\ChargeableItem;
use App\Models\ChargeableItemCategory;
use App\Models\ChargeableItemPrice;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);

describe('chargeable-item-price.index', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->getJson(route('chargeable-item-price.index'));

        $response->assertUnauthorized();
    });

    //@@TODO: Implement remaining tests
});

//@@TODO: Implement remaining tests
