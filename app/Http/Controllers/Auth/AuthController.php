<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\User;

class AuthController extends ApiController
{
    private $client;
    
    public function __construct()
    {
        $this->client = \DB::table('oauth_clients')
            ->where('id', config('auth.passport.password.client_id'))
            ->first();
    }
    
    protected function login(LoginRequest $request)
    {
        $request->request->add([
            'username' => $request->username,
            'password' => $request->password,
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => ''
        ]);
    
        $proxy = \Request::create(
            'oauth/token',
            'POST'
        );
    
        return \Route::dispatch($proxy);
    }
    
    protected function signup(SignupRequest $request)
    {
        $user = User::create($request->only('email', 'password', 'name'));
        
        $request->request->add([
            'username' => $user->name,
            'password' => $user->password,
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'user_id' => $user->id,
            'scope' => ''
        ]);
    
        $proxy = \Request::create(
            'oauth/authorize',
            'POST'
        );
    
        return \Route::dispatch($proxy);
    }
    
    protected function refresh(LoginRequest $request)
    {
        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
        ]);
    
        $proxy = \Request::create(
            '/oauth/token',
            'POST'
        );
    
        return \Route::dispatch($proxy);
    }
}
