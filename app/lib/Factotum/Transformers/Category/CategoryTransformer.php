<?php

namespace Factotum\Transformers\Category;

use App\Models\Categories\Category;
use Factotum\Transformers\DummyTransformer;

class CategoryTransformer extends DummyTransformer
{
    /**
     * @param Category $model
     * @return array
     */
    public function transform($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
        ];
    }
}