<?php

namespace Factotum\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal;

/**
 * Class DummyTransformer
 *
 * @package app\lib\Factotum\Transformers
 */
class DummyTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param Model $model
     * @return array
     */
    public function transform($model)
    {
        return $model->toArray();
    }
}