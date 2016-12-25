<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Categories\Category;
use Factotum\Transformers\Category\CategoryTransformer;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 *
 * @package App\Http\Controllers\Category
 */
class CategoryController extends ApiController
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $auth;
    
    /**
     * CategoryController constructor.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $paginate = Category::where('user_id', $this->auth->id())
            ->paginate($request->input('limit', $this->defaultLimit));
        
        return $this->respondWithCollection(
            $paginate,
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
                    'user_id' => $this->auth->id(),
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
            Category::where('user_id', $this->auth->id())->findOrFail($id),
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
        $category = Category::where('user_id', $this->auth->id())->findOrFail($id);
        $category->update(array_merge($request->except('user_id'), ['user_id' => $this->auth->id()]));
        
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
        Category::where('user_id', $this->auth->id())->findOrFail($id)->delete();
        
        return $this->respondWithOk();
    }
}
