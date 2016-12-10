<?php

namespace Factotum\User;

use App\User;

/**
 * Class UserRepository
 *
 * @package Factotum\User
 */
class UserRepository
{
    /**
     * @param \App\User $user
     */
    public function activate(User $user)
    {
        $user->active = 1;
        $user->save();
    }
    
    /**
     * @param \App\User $user
     */
    public function deactivate(User $user)
    {
        $user->active = 0;
        $user->save();
    }
    
    /**
     * @param \App\User $user
     */
    public function confirm(User $user)
    {
        
    }
    
    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @return User
     */
    public function create($username, $password, $email)
    {
        return User::create([
            'name' => $username,
            'email' => $email,
            'password' => bcrypt($password)
        ]);
    }
}