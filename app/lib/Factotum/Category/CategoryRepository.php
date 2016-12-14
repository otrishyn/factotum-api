<?php

namespace Factotum\Category;


use App\Models\Categories\Category;
use App\Models\User;

class CategoryRepository
{
    public function create(array $attributes, User $user)
    {
        return Category::create(array_merge($attributes, ['user_id' => $user->id]));
    }
}