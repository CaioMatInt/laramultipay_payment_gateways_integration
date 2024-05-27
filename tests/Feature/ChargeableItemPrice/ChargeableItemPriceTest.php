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

    test('should return all chargeable item prices related to an chargeable item', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'chargeable_item_category_id' => $chargeableItemCategory->id
        ]);

        $chargeableItemPrices = ChargeableItemPrice::factory()->count(3)->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'chargeable_item_id' => $chargeableItem->id
        ]);

        $response = $this->getJson(route('chargeable-item-price.index', ['chargeable_item_id' => $chargeableItem->id]));

        $response->assertOk();

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'price',
                    'currency',
                    'chargeable_item' => [
                        'id',
                        'name',
                        'description',
                        'category' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at'
                        ],
                        'created_at',
                        'updated_at'
                    ],
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $responseData = $response->json()['data'];

        foreach ($responseData as $key => $chargeableItemPrice) {
            expect($chargeableItemPrice['id'])->toBe($chargeableItemPrices[$key]->id)
                ->and($chargeableItemPrice['price'])->toBe($chargeableItemPrices[$key]->price)
                ->and($chargeableItemPrice['currency'])->toBe($chargeableItemPrices[$key]->currency)
                ->and($chargeableItemPrice['created_at'])->toBe(
                    $chargeableItemPrices[$key]->created_at->format('Y-m-d\TH:i:s.u\Z')
                )
                ->and($chargeableItemPrice['updated_at'])->toBe(
                    $chargeableItemPrices[$key]->updated_at->format('Y-m-d\TH:i:s.u\Z')
                )

                ->and($chargeableItemPrice['chargeable_item']['id'])->toBe($chargeableItem->id)
                ->and($chargeableItemPrice['chargeable_item']['name'])->toBe($chargeableItem->name)
                ->and($chargeableItemPrice['chargeable_item']['description'])->toBe($chargeableItem->description)
                ->and($chargeableItemPrice['chargeable_item']['created_at'])->toBe(
                    $chargeableItem->created_at->format('Y-m-d\TH:i:s.u\Z')
                )
                ->and($chargeableItemPrice['chargeable_item']['updated_at'])->toBe(
                    $chargeableItem->updated_at->format('Y-m-d\TH:i:s.u\Z')
                )

                ->and($chargeableItemPrice['chargeable_item']['category']['id'])->toBe($chargeableItemCategory->id)
                ->and($chargeableItemPrice['chargeable_item']['category']['name'])->toBe($chargeableItemCategory->name)
                ->and($chargeableItemPrice['chargeable_item']['category']['created_at'])->toBe(
                    $chargeableItemCategory->created_at->format('Y-m-d\TH:i:s.u\Z')
                )
                ->and($chargeableItemPrice['chargeable_item']['category']['updated_at'])->toBe(
                    $chargeableItemCategory->updated_at->format('Y-m-d\TH:i:s.u\Z')
                );
        }
    });

    test('should return empty data array if there are no chargeable item prices', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'chargeable_item_category_id' => $chargeableItemCategory->id
        ]);

        $response = $this->getJson(route('chargeable-item-price.index', ['chargeable_item_id' => $chargeableItem->id]));

        $response->assertOk();

        $response->assertJsonCount(0, 'data');
    });
});

describe('chargeable-item-price.show', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItemPrice = ChargeableItemPrice::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item-price.show', $chargeableItemPrice->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item price does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item-price.show', [
            'chargeable_item_id' => $chargeableItem->id,
            'id' => 1
        ]));

        $response->assertNotFound();
    });

    test('should return 422 if chargeable item does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('chargeable-item-price.show', [
            'chargeable_item_id' => 1,
            'id' => 1
        ]));

        $response->assertStatus(422);

        expect($response->json('errors.chargeable_item_id.0'))->toContain('The chargeable item does not exist.');
    });

    test('should return chargeable item price', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'chargeable_item_category_id' => $chargeableItemCategory->id
        ]);

        $chargeableItemPrice = ChargeableItemPrice::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'chargeable_item_id' => $chargeableItem->id
        ]);

        $response = $this->getJson(route('chargeable-item-price.show', [
            'chargeable_item_id' => $chargeableItem->id,
            'id' => $chargeableItemPrice->id
        ]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $chargeableItemPrice->id,
                'price' => $chargeableItemPrice->price,
                'currency' => $chargeableItemPrice->currency,
                'chargeable_item' => [
                    'id' => $chargeableItem->id,
                    'name' => $chargeableItem->name,
                    'description' => $chargeableItem->description,
                    'category' => [
                        'id' => $chargeableItemCategory->id,
                        'name' => $chargeableItemCategory->name,
                        'created_at' => $chargeableItemCategory->created_at->format('Y-m-d\TH:i:s.u\Z'),
                        'updated_at' => $chargeableItemCategory->updated_at->format('Y-m-d\TH:i:s.u\Z')
                    ],
                    'created_at' => $chargeableItem->created_at->format('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => $chargeableItem->updated_at->format('Y-m-d\TH:i:s.u\Z')
                ],
                'created_at' => $chargeableItemPrice->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $chargeableItemPrice->updated_at->format('Y-m-d\TH:i:s.u\Z')
            ]
        ]);
    });
});

//@@TODO: Implement remaining tests
