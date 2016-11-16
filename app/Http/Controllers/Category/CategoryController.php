<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Categories\Category;
use Factotum\Transformers\Category\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        return $this->respondWithCollection(
            Category::paginate($request->input('limit', $this->defaultLimit)),
            new CategoryTransformer(),
            'category',
            explode(',', $request->input('include', ''))
        );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function store(Request $request)
    {
        return $this->respondWithItem(
            Category::create(
                [
                    'name' => $request->input('name'),
                    'queue' => Category::max('queue') + 1,
                ]
            ),
            new CategoryTransformer(),
            'category'
        );
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return array
     */
    public function show($id)
    {
        return $this->respondWithItem(
            Category::findOrFail($id),
            new CategoryTransformer(),
            'category'
        );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        
        return $this->respondWithItem(
            $category,
            new CategoryTransformer(),
            'category'
        );
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        
        return $this->respondWithOk();
    }
}
