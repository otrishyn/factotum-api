<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Categories\Type;
use Factotum\Transformers\Category\TypeTransformer;
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
     * @param mixed $id
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index($categoryId, $id, Request $request)
    {
        return $this->respondWithCollection(
            Type::where('category_id',$id)->paginate($request->input('limit', $this->defaultLimit)),
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
        $type = Type::findOrFail($id);
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
            Type::findOrFail($id),
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
        Type::findOrFail($id)->delete();
    
        return $this->respondWithOk();
    }
}
