<?php

use App\Models\ChargeableItemCategory;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);

describe('chargeable-item-categories.index', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->getJson(route('chargeable-item-category.index'));

        $response->assertUnauthorized();
    });

    test('should return all chargeable item categories related to the users company', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategories = ChargeableItemCategory::factory()->count(3)->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item-category.index'));

        $response->assertOk();

        $response->assertJsonCount(3, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $responseData = $response->json()['data'];

        foreach ($responseData as $key => $chargeableItemCategory) {
            expect($chargeableItemCategory['id'])->toBe($chargeableItemCategories[$key]->id)
                ->and($chargeableItemCategory['name'])->toBe($chargeableItemCategories[$key]->name)
                ->and($chargeableItemCategory['created_at'])->toBe(
                    $chargeableItemCategories[$key]->created_at->format('Y-m-d\TH:i:s.u\Z')
                )
                ->and($chargeableItemCategory['updated_at'])->toBe(
                    $chargeableItemCategories[$key]->updated_at->format('Y-m-d\TH:i:s.u\Z')
                );
        }
    });

    test('should return empty data array if there are no payment gateway keys', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('chargeable-item-category.index'));

        $response->assertOk();

        $response->assertJsonCount(0, 'data');
    });
});

describe('chargeable-item-categories.show', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item-category.show', $chargeableItemCategory->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item category does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->getJson(route('chargeable-item-category.show', 1));

        $response->assertNotFound();
    });

    test('should return chargeable item category', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->getJson(route('chargeable-item-category.show', $chargeableItemCategory->id));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $chargeableItemCategory->id,
                'name' => $chargeableItemCategory->name,
                'created_at' => $chargeableItemCategory->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $chargeableItemCategory->updated_at->format('Y-m-d\TH:i:s.u\Z')
            ]
        ]);
    });
});

describe('chargeable-item-categories.store', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $response = $this->postJson(route('chargeable-item-category.store'));

        $response->assertUnauthorized();
    });

    test('should return 422 if name is missing', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item-category.store'), []);

        $response->assertStatus(422);
    });

    test('should return 422 if name is greater than 255 characters', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->postJson(route('chargeable-item-category.store'), [
            'name' => str_repeat('a', 256)
        ]);

        $response->assertStatus(422);
    });

    test('should return 201 if chargeable item category is created', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->make();

        $response = $this->postJson(route('chargeable-item-category.store'), [
            'name' => $chargeableItemCategory->name
        ]);

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'name' => $chargeableItemCategory->name
            ]
        ]);
    });
});

describe('chargeable-item-categories.update', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->patchJson(route('chargeable-item-category.update', $chargeableItemCategory->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item category does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);
        $name = "name";

        $response = $this->patchJson(route('chargeable-item-category.update', 1), [
            'name' => $name
        ]);

        $response->assertNotFound();
    });

    test('should return 422 if name is greater than 255 characters', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->patchJson(route('chargeable-item-category.update', $chargeableItemCategory->id), [
            'name' => str_repeat('a', 256)
        ]);

        $response->assertStatus(422);
    });

    test('should return 200 if chargeable item category is updated', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $newName = 'New Name';

        $response = $this->patchJson(route('chargeable-item-category.update', $chargeableItemCategory->id), [
            'name' => $newName
        ]);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $chargeableItemCategory->id,
                'name' => $newName,
                'created_at' => $chargeableItemCategory->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $chargeableItemCategory->updated_at->format('Y-m-d\TH:i:s.u\Z')
            ]
        ]);
    });
});

describe('chargeable-item-categories.delete', function () {
    beforeEach(function () {
        test()->mockCompanyAdminUser();
    });

    test('should return 401 if user is not authenticated', function () {
        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->deleteJson(route('chargeable-item-category.destroy', $chargeableItemCategory->id));

        $response->assertUnauthorized();
    });

    test('should return 404 if chargeable item category does not exist', function () {
        $this->actingAs($this->userCompanyAdmin);

        $response = $this->deleteJson(route('chargeable-item-category.destroy', 9999999));

        $response->assertNotFound();
    });

    test('should return 204 if chargeable item category is deleted', function () {
        $this->actingAs($this->userCompanyAdmin);

        $chargeableItemCategory = ChargeableItemCategory::factory()->create([
            'company_id' => $this->userCompanyAdmin->company_id
        ]);

        $response = $this->deleteJson(route('chargeable-item-category.destroy', $chargeableItemCategory->id));

        $response->assertNoContent();
    });
});
