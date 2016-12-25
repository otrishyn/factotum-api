<?php

namespace App\Http\Controllers;

use Factotum\Transformers\DummyTransformer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\TransformerAbstract;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     *
     */
    const CODE_WRONG_ARGS = 'GEN-FUBARGS';
    /**
     *
     */
    const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';
    /**
     *
     */
    const CODE_INTERNAL_ERROR = 'GEN-AAAGGH';
    /**
     *
     */
    const CODE_UNAUTHORIZED = 'GEN-MATBGTFO';
    /**
     *
     */
    const CODE_FORBIDDEN = 'GEN-GTFO';
    
    /**
     * @var int
     */
    protected $defaultLimit = 10;

    /**
     * @param Model $item
     * @param \League\Fractal\TransformerAbstract $transformer
     * @param null $resourceName
     * @param array $includes
     * @param array $meta
     * @return array
     */
    protected function respondWithItem(
        $item,
        TransformerAbstract $transformer,
        $resourceName = null,
        array $includes = [],
        array $meta = []
    ) {
        return $this->makeFractal($transformer, $includes, $meta)
            ->item($item, null, $resourceName)
            ->toArray();
    }

    /**
     * @param Collection|LengthAwarePaginator $items
     * @param TransformerAbstract $transformer
     * @param null $resourceName
     * @param array $includes
     * @param array $meta
     * @return array
     */
    protected function respondWithCollection(
        $items,
        TransformerAbstract $transformer,
        $resourceName = null,
        array $includes = [],
        array $meta = []
    ) {
        $fractal = $this->makeFractal($transformer, $includes, $meta);

        if ($items instanceof LengthAwarePaginator) {
            $fractal->paginateWith(new IlluminatePaginatorAdapter($items));

            $items = $items->getCollection();
        }

        return $fractal->collection($items, null, $resourceName)->toArray();
    }
    
    /**
     * @param int $status
     * @param array $body
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithBody($status = 200, array $body, array $headers = [])
    {
        return \Response::json($body, $status, $headers);
    }
    
    /**
     * @param $status
     * @param $message
     * @param $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($status, $message, $errorCode)
    {
        return $this->respondWithBody(
            $status,
            [
                'error' => [
                    'code' => $errorCode,
                    'http_code' => $status,
                    'message' => $this->getDefaultStatusMessage($status, $message),
                ],
            ]
        );
    }

    /**
     * @param \League\Fractal\TransformerAbstract $transformer
     * @param array $includes
     * @param array $meta
     * @return \Spatie\Fractal\Fractal
     */
    protected function makeFractal(TransformerAbstract $transformer = null, array $includes = [], array $meta = [])
    {
        /**
         * @var \Spatie\Fractal\Fractal $fractal
         */
        $fractal = fractal()
            ->parseIncludes($includes)
            ->addMeta($meta);

        if (is_null($transformer)) {
            $transformer = new DummyTransformer;
        }
        $fractal->transformWith($transformer);

        return $fractal;
    }
    
    /**
     * @param array $body
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithOk(array $body = [], array $headers = [])
    {
        return $this->respondWithBody(200, $body, $headers);
    }
    
    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorForbidden($message = null)
    {
        return $this->respondWithError(403, $message, self::CODE_FORBIDDEN);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorInternalError($message = null)
    {
        return $this->respondWithError(500, $message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorNotFound($message = null)
    {
        return $this->respondWithError(404, $message, self::CODE_NOT_FOUND);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorUnauthorized($message = null)
    {
        return $this->respondWithError(401, $message, self::CODE_UNAUTHORIZED);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorWrongArgs($message = null)
    {
        return $this->respondWithError(400, $message, self::CODE_WRONG_ARGS);
    }

    /**
     * @param int $status
     * @param string $default
     * @return string
     */
    protected function getDefaultStatusMessage($status, $default = null)
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