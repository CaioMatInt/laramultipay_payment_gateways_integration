<?php

use App\Models\ChargeableItem;
use App\Models\ChargeableItemCategory;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);

describe('chargeable-item.index', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->getJson(route('chargeable-item.index'));

        $response->assertUnauthorized();
    });

    test('should return all chargeable items related to the users company', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItems = ChargeableItem::factory()->count(3)->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item.index'));

        $response->assertOk();

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'category',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $responseData = $response->json()['data'];

        foreach ($responseData as $key => $data) {
            expect($data['id'])->toBe($chargeableItems[$key]->id)
                ->and($data['name'])->toBe($chargeableItems[$key]->name)
                ->and($data['description'])->toBe($chargeableItems[$key]->description)
                ->and($data['created_at'])->toBe($chargeableItems[$key]->created_at->format('Y-m-d\TH:i:s.u\Z'))
                ->and($data['updated_at'])->toBe($chargeableItems[$key]->updated_at->format('Y-m-d\TH:i:s.u\Z'));

            $categoryResult = $chargeableItems[$key]->load('category')->category;
            expect($data['category']['name'])->toBe($categoryResult->name)
                ->and($data['category']['created_at'])->toBe($categoryResult->created_at->format('Y-m-d\TH:i:s.u\Z'))
                ->and($data['category']['updated_at'])->toBe($categoryResult->updated_at->format('Y-m-d\TH:i:s.u\Z'));
        }
    });

    test('should return empty data array if there are no chargeable items', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('chargeable-item.index'));

        $response->assertOk();

        $response->assertJsonCount(0, 'data');
    });
});

describe('chargeable-items.show', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item.show', $chargeableItem->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('chargeable-item.show', 1));

        $response->assertNotFound();
    });

    test('should return chargeable item', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id,
            'chargeable_item_category_id' => $chargeableItemCategory->id
        ]);

        $response = $this->getJson(route('chargeable-item.show', $chargeableItem->id));

        $response->assertOk();

        $response->assertJson([
            'data' => [
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
            ]
        ]);
    });
});

describe('chargeable-items.store', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->postJson(route('chargeable-item.store'));

        $response->assertUnauthorized();
    });

    test('should return 422 if name is missing', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item.store'), []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('name');
        expect($response->json('errors.name.0'))->toBe('The name field is required.');
    });

    test('should return 422 if name is greater than 255 characters', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item.store'), [
            'name' => str_repeat('a', 256)
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('name');
        expect($response->json('errors.name.0'))->toBe('The name field must not be greater than 255 characters.');
    });

    test('should return 422 if description is missing', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item.store'), [
            'name' => 'name'
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('description');
        expect($response->json('errors.description.0'))->toBe('The description field is required.');
    });

    test('should return 422 if chargeable_item_category_id is missing', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item.store'), [
            'name' => 'name',
            'description' => 'description'
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('chargeable_item_category_id');
        expect($response->json('errors.chargeable_item_category_id.0'))->toBe('The chargeable item category id field is required.');
    });

    test('should return 422 if chargeable_item_category_id doesnt exists in the database', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item.store'), [
            'name' => 'name',
            'description' => 'description',
            'chargeable_item_category_id' => 9999999
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('chargeable_item_category_id');
        expect($response->json('errors.chargeable_item_category_id.0'))->toBe('The selected chargeable item category id is invalid.');
    });

    test('should return 201 if chargeable item is created', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);
        $chargeableItem = ChargeableItem::factory()->make();

        $response = $this->postJson(route('chargeable-item.store'), [
            'name' => $chargeableItem->name,
            'description' => $chargeableItem->description,
            'chargeable_item_category_id' => $chargeableItemCategory->id
        ]);

        $response->assertCreated();

        $response->assertJson([
            'data' => [
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

            ]
        ]);
    });
});

describe('chargeable-items.update', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->patchJson(route('chargeable-item.update', $chargeableItem->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);
        $name = "name";
        $description = "description";

        $response = $this->patchJson(route('chargeable-item.update', 1), [
            'name' => $name,
            'description' => $description
        ]);

        $response->assertNotFound();
    });

    test('should return 422 if name is greater than 255 characters', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->patchJson(route('chargeable-item.update', $chargeableItem->id), [
            'name' => str_repeat('a', 256)
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('name');
        expect($response->json('errors.name.0'))->toBe('The name field must not be greater than 255 characters.');
    });

    test('should return 422 if chargeable_item_category_id doesnt exists in the database', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->patchJson(route('chargeable-item.update', $chargeableItem->id), [
            'name' => 'name',
            'description' => 'description',
            'chargeable_item_category_id' => 9999999
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('chargeable_item_category_id');
        expect($response->json('errors.chargeable_item_category_id.0'))->toBe('The selected chargeable item category id is invalid.');
    });

    test('should return 200 if chargeable item is updated', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $newName = 'New Name';
        $newDescription = 'New Description';

        $newChargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->patchJson(route('chargeable-item.update', $chargeableItem->id), [
            'name' => $newName,
            'description' => $newDescription,
            'chargeable_item_category_id' => $newChargeableItemCategory->id
        ]);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $chargeableItem->id,
                'name' => $newName,
                'description' => $newDescription,
                'category' => [
                    'id' => $newChargeableItemCategory->id,
                    'name' => $newChargeableItemCategory->name,
                    'created_at' => $newChargeableItemCategory->created_at->format('Y-m-d\TH:i:s.u\Z'),
                    'updated_at' => $newChargeableItemCategory->updated_at->format('Y-m-d\TH:i:s.u\Z')
                ],
                'created_at' => $chargeableItem->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $chargeableItem->updated_at->format('Y-m-d\TH:i:s.u\Z')
            ]
        ]);
    });
});

describe('chargeable-items.delete', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->deleteJson(route('chargeable-item.destroy', $chargeableItem->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->deleteJson(route('chargeable-item.destroy', 9999999));

        $response->assertNotFound();
    });

    test('should return 204 if chargeable item is deleted', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItem = ChargeableItem::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->deleteJson(route('chargeable-item.destroy', $chargeableItem->id));

        $response->assertNoContent();
    });
});
