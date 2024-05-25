<?php

namespace App\Traits\Database;

trait DestroyableTrait
{
    use ModelAccessorTrait;

    /**
     * Destroy a model by its primary key.
     *
     * @param int $id
     * @return void
     */
    public function destroyRecord(int $id): void
    {
        $model = $this->getModel()->findOrFail($id);
        $model->delete();
    }
}
