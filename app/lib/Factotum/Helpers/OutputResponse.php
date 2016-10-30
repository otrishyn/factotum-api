<?php

namespace Factotum\Helpers;


use Factotum\Transformers\DummyTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class OutputResponse
{
    public function output($data, $transformer = null, $includes = [], $meta = [])
    {
        $fractal = fractal()
            ->parseIncludes($includes)
            ->addMeta($meta);

        if ($data instanceof LengthAwarePaginator) {
            $fractal->paginateWith(new IlluminatePaginatorAdapter($data));

            $data = $data->getCollection();
        }

        if (is_null($transformer)) {
            $transformer = new DummyTransformer;
        }

        $fractal->transformWith($transformer);

        if ($data instanceof Collection) {
            $fractal->collection($data);
        } else {
            $fractal->item($data);
        }

        return $fractal->toArray();
    }

    public function statusResponse($status = 200, $body = null)
    {
        response($this->getStatusText($status, $body), $body);
    }

    /**
     * @param int $status
     * @param mixed $default
     * @return string
     */
    private function getStatusText($status, $default)
    {
        if (! is_null($default)) {
            return $default;
        }
        if (isset(\Symfony\Component\HttpFoundation\Response::$statusTexts[$status])) {
            return \Symfony\Component\HttpFoundation\Response::$statusTexts[$status];
        }

        return 'Unknown response type';
    }
}