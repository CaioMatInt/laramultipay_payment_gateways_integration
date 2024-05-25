<?php

namespace App\Traits\Database;

use Illuminate\Support\Facades\Cache;

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
