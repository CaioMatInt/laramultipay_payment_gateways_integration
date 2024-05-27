<?php

namespace App\Http\Controllers;

use App\DTOs\ChargeableItemPrice\ChargeableItemPriceDto;
use App\Http\Requests\ChargeableItemPrice\IndexChargeableItemPriceRequest;
use App\Http\Requests\ChargeableItemPrice\ShowChargeableItemPriceRequest;
use App\Http\Requests\ChargeableItemPrice\StoreChargeableItemPriceRequest;
use App\Http\Requests\ChargeableItemPrice\UpdateChargeableItemPriceRequest;
use App\Http\Resources\ChargeableItemPrice\ChargeableItemPriceResource;
use App\Services\ChargeableItemPrice\ChargeableItemPriceService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ChargeableItemPriceController extends Controller
{

    public function __construct(private readonly ChargeableItemPriceService $service)
    { }

    public function index(IndexChargeableItemPriceRequest $request): ResourceCollection
    {
        $chargeableItemPrices = $this->service->getByChargeableItemId($request->chargeable_item_id);
        return ChargeableItemPriceResource::collection($chargeableItemPrices);
    }

    public function show(ShowChargeableItemPriceRequest $request): ChargeableItemPriceResource
    {
        $chargeableItemPrice = $this->service->findByIdAndChargeableItemId(
            $request->id,
            $request->chargeable_item_id
        );
        return new ChargeableItemPriceResource($chargeableItemPrice);
    }

    public function store(StoreChargeableItemPriceRequest $request): ChargeableItemPriceResource
    {
        $dto = new ChargeableItemPriceDto($request->only(['currency', 'price', 'chargeable_item_id']));
        $chargeableItemPrice = $this->service->store($dto);
        return new ChargeableItemPriceResource($chargeableItemPrice);
    }

    public function update(int $id, UpdateChargeableItemPriceRequest $request): ChargeableItemPriceResource
    {
        $dto = new ChargeableItemPriceDto($request->only(['currency', 'price', 'chargeable_item_id']));
        $chargeableItemPrice = $this->service->update($id, $dto);
        return new ChargeableItemPriceResource($chargeableItemPrice);
    }

    public function destroy(int $id): Response
    {
        $this->service->destroyRecord($id);
        return response()->noContent();
    }
}
