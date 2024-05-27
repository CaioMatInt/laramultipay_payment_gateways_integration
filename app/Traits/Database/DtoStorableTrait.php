<?php

namespace App\Traits\Database;

use App\DTOs\BaseDto;
use Illuminate\Database\Eloquent\Model;

trait DtoStorableTrait
{
    use ModelAccessorTrait;

    /**
     * Store using a DTO and with the company_id of the authenticated user
     *
     * @param BaseDto $dto
     * @return Model
     */
    public function storeWithDtoAndAuthUserCompanyId(BaseDto $dto): Model
    {
        $data = array_merge($dto->toArray(), ['company_id' => auth()->user()->company_id]);
        return $this->getModel()->create($data);
    }

    /**
     * Store using a DTO
     *
     * @param BaseDto $dto
     * @return Model
     */
    public function storeWithDto(BaseDto $dto): Model
    {
        return $this->getModel()->create($dto->toArray());
    }
}
