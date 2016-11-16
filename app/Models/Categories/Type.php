<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Type
 *
 * @package App\Models\Categories
 */
class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
