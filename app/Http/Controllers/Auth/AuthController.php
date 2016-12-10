<?php

namespace app\Http\Controllers\Auth;


use App\Http\Controllers\ApiController;
use app\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SignInRequest;

class AuthController extends ApiController
{
    protected function register(RegisterRequest $request)
    {
        
    }
    
    protected function token(SignInRequest $request)
    {
    }
    
    protected function refreshToken(RefreshTokenRequest $request)
    {
        
    }
}