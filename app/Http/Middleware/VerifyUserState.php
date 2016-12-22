<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;

/**
 * Class VerifyUserState
 *
 * @package App\Http\Middleware
 */
class VerifyUserState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        $user = $request->user($guard);
        if (! $user || ! $user->isActive()) {
            throw new AuthenticationException;
        }
        
        return $next($request);
    }
}