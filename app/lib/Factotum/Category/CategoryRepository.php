<?php

namespace Factotum\Category;

use App\Models\Categories\Category;
use App\Models\User;

/**
 * Class CategoryRepository
 *
 * @package Factotum\Category
 */
class CategoryRepository
{
    /**
     * @param array $attributes
     * @param \App\Models\User $user
     * @return Category
     */
    public function create(array $attributes, User $user)
    {
        return Category::create(array_merge($attributes, ['user_id' => $user->id]));
    }
    
    /**
     * @param string $id
     * @param \App\Models\User $user
     * @return Category|null
     */
    public function findUserCategoryById($id, User $user)
    {
        return Category::where('user_id', $user->id)->find($id);
    }
}