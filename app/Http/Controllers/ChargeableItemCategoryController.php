<?php

namespace App\Http\Controllers;

use App\DTOs\ChargeableItemCategory\ChargeableItemCategoryDto;
use App\Http\Resources\ChargeableItemCategory\ChargeableItemCategoryResource;
use App\Services\ChargeableItemCategory\ChargeableItemCategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ChargeableItemCategoryController extends Controller
{
    //@@TODO Add user type validation
    public function __construct(private readonly ChargeableItemCategoryService $service)
    { }

    public function index(): ResourceCollection
    {
        $chargeableItemCategories = $this->service->getPaginatedByCompanyId(
            auth()->user()->company_id,
            $request->perPage ?? config('database.pagination.default_records_per_page')
        );
        return ChargeableItemCategoryResource::collection($chargeableItemCategories);
    }

    public function show(int $id): ChargeableItemCategoryResource
    {
        $chargeableItemCategory = $this->service->findCached($id);
        return new ChargeableItemCategoryResource($chargeableItemCategory);
    }

    //@@TODO: Add request validation
    public function store(Request $request): ChargeableItemCategoryResource
    {
        $dto = new ChargeableItemCategoryDto($request->only('name'));
        $chargeableItemCategory = $this->service->store($dto);
        return new ChargeableItemCategoryResource($chargeableItemCategory);
    }

    //@@TODO: Add request validation
    public function update(Request $request, int $id): ChargeableItemCategoryResource
    {
        $dto = new ChargeableItemCategoryDto($request->only('name'));
        $chargeableItemCategory = $this->service->update($id, $dto);
        return new ChargeableItemCategoryResource($chargeableItemCategory);
    }

    //@@TODO: Add request validation
    public function destroy(int $id): Response
    {
        $this->service->destroy($id);
        return response()->noContent();
    }
}
