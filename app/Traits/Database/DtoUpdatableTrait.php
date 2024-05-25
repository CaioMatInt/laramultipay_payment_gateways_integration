<?php

namespace App\Traits\Database;

use App\DTOs\BaseDto;
use Illuminate\Database\Eloquent\Model;

trait DtoUpdatableTrait
{
    use ModelAccessorTrait;

    /**
     * Update a model by its primary key.
     *
     * @param int $id
     * @param BaseDto $dto
     * @return Model
     */
    public function updateWithDto(int $id, BaseDto $dto): Model
    {
        $model = $this->getModel()->findOrFail($id);
        $model->update($dto->toArray());
        return $model;
    }
}
