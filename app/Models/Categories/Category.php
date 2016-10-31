<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property mixed name
 * @property mixed id
 * @package App\Models\Categories
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'queue',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function types()
    {
        return $this->hasMany(Type::class);
    }
}
