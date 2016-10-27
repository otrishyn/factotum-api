<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @package App\Models\Categories
 */
class Category extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function types()
    {
        return $this->hasMany(Type::class);
    }
}
