<?php

namespace App\Traits\Database;

use App\DTOs\BaseDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait DtoUpdatableTrait
{
    use ModelAccessorTrait;

    /**
     * Update a model by its primary key.
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function updateWithDto(int $id, BaseDto $dto): Model
    {
        $model = $this->getModel()->findOrFail($id);
        $model->update($dto->toArray());
        return $model;
    }
}
