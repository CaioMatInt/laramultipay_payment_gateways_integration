<?php

namespace App\Http\Controllers;

use App\DTOs\ChargeableItem\ChargeableItemDto;
use App\Http\Requests\ChargeableItem\StoreChargeableItemRequest;
use App\Http\Requests\ChargeableItem\UpdateChargeableItemRequest;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\ChargeableItem\ChargeableItemResource;
use App\Services\ChargeableItem\ChargeableItemService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ChargeableItemController extends Controller
{
    //@@TODO Add user type validation
    public function __construct(private readonly ChargeableItemService $service)
    { }

    public function index(PaginatedRequest $request): ResourceCollection
    {
        $chargeableItems = $this->service->getPaginatedByCompanyId(
            auth()->user()->company_id,
            $request->perPage ?? config('database.pagination.default_records_per_page')
        );
        return ChargeableItemResource::collection($chargeableItems);
    }

    public function show(int $id): ChargeableItemResource
    {
        $chargeableItemCategory = $this->service->findCached($id);
        return new ChargeableItemResource($chargeableItemCategory);
    }

    public function store(StoreChargeableItemRequest $request): ChargeableItemResource
    {
        $dto = new ChargeableItemDto($request->only(
            'name',
            'description',
            'chargeable_item_category_id'
        ));
        $chargeableItemCategory = $this->service->store($dto);
        return new ChargeableItemResource($chargeableItemCategory);
    }

    public function update(UpdateChargeableItemRequest $request, int $id): ChargeableItemResource
    {
        $dto = new ChargeableItemDto($request->only(
            'name',
            'description',
            'chargeable_item_category_id'
        ));
        $chargeableItemCategory = $this->service->update($id, $dto);
        return new ChargeableItemResource($chargeableItemCategory);
    }

    public function destroy(int $id): Response
    {
        $this->service->destroyRecord($id);
        return response()->noContent();
    }
}
