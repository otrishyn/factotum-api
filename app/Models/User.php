<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @property mixed id
 * @property mixed name
 * @property mixed email
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function findForPassport($identity)
    {
        /**
         * var Builder $builder
         */
        $builder = (! ! filter_var($identity, FILTER_VALIDATE_EMAIL))
            ? $this->where('email', $identity)
            : $this->where('name', $identity);
        
        return $builder->active()->first();
    }
    
    /**
     * Scope: Active
     *
     * @param  $builder  Builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('active', 1);
    }
    
    public function isActive()
    {
        return $this->active == 1;
    }
}
