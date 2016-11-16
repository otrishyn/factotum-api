<?php

namespace Factotum\Transformers\Category;

use App\Models\Categories\Category;
use Factotum\Transformers\DummyTransformer;

/**
 * Class CategoryTransformer
 *
 * @package Factotum\Transformers\Category
 */
class CategoryTransformer extends DummyTransformer
{
    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = [
        'type'
    ];
    
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
    
    /**
     * @param \App\Models\Categories\Category $category
     * @return \League\Fractal\Resource\Collection
     */
    public function includeType(Category $category)
    {
        return $this->collection($category->types, new TypeTransformer(), 'type');
    }
}