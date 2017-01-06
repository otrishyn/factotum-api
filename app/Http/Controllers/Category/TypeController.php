<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Categories\Type;
use Factotum\Transformers\Category\TypeTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class TypeController
 *
 * @package App\Http\Controllers\Category
 */
class TypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param mixed $categoryId
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index($categoryId, Request $request)
    {
        return $this->respondWithCollection(
            Type::where('category_id', $categoryId)->paginate($request->input('limit', $this->defaultLimit)),
            new TypeTransformer(),
            'type'
        );
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $categoryId
     * @return array
     */
    public function store(Request $request, $categoryId)
    {
        return $this->respondWithItem(
            Type::create(
                [
                    'name' => $request->input('name'),
                    'category_id' => $categoryId
                ]
            ),
            new TypeTransformer(),
            'type'
        );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param mixed $categoryId
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $categoryId, $id)
    {
        $type = $this->findOrFailType($categoryId, $id);
        $type->update($request->all());
        
        return $this->respondWithItem(
            $type,
            new TypeTransformer(),
            'type'
        );
    }
    
    /**
     * Display the specified resource.
     *
     * @param mixed $categoryId
     * @param  mixed $id
     * @return array
     */
    public function show($categoryId, $id)
    {
        return $this->respondWithItem(
            $this->findOrFailType($categoryId, $id),
            new TypeTransformer(),
            'type'
        );
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $categoryId
     * @param  mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($categoryId, $id)
    {
        $this->findOrFailType($categoryId, $id)->delete();
    
        return $this->respondWithOk();
    }
    
    /**
     * @param mixed $categoryId
     * @param mixed $id
     * @return Type|null
     */
    protected function findType($categoryId, $id)
    {
        return Type::where('category_id', $categoryId)
            ->where('id', $id)
            ->get();
    }
    
    /**
     * @param mixed $categoryId
     * @param mixed $id
     * @return mixed
     */
    protected function findOrFailType($categoryId, $id)
    {
        $type = $this->findType($categoryId, $id);
        if (! $type) {
            throw (new ModelNotFoundException)->setModel(Type::class);
        }
        return $type;
    }
}
