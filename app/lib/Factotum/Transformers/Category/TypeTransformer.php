<?php

namespace Factotum\Transformers\Category;

use App\Models\Categories\Category;
use App\Models\Categories\Type;
use Factotum\Transformers\DummyTransformer;

class TypeTransformer extends DummyTransformer
{
    /**
     * @param Type $model
     * @return array
     */
    public function transform($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'categoryId' => $model->category_id
        ];
    }
}