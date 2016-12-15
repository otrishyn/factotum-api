<?php

namespace app\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\RegisterRequest;
use Factotum\Auth\OauthClientRepository;
use Factotum\User\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * @package app\Http\Controllers\Auth
 */
class AuthController extends ApiController
{
    /**
     * @var int
     */
    private $clientId;
    /**
     * @var string
     */
    private $clientSecret;
    /**
     * @var \Factotum\User\UserRepository
     */
    private $userRepository;
    
    /**
     * AuthController constructor.
     *
     * @param \Factotum\User\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->clientId = config('auth.password_grand_id');
        $this->clientSecret = config('auth.password_grand_secret');
        $this->userRepository = $userRepository;
    }
    
    /**
     * @param \App\Http\Requests\RegisterRequest $request
     */
    protected function register(RegisterRequest $request)
    {
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function token(Request $request)
    {
        $validator = \Validator::make($request->only(['email', 'password']), [
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);
        
        if ($validator->fails()) {
            throw new AuthenticationException();
        }
    
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
    
        return $this->proxy(
            $request,
            [
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'grant_type' => 'password',
                'scope' => '*',
            ],
            $proxy
        );
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function refreshToken(Request $request)
    {
        $validator = \Validator::make($request->only(['refresh_token']), [
            'refresh_token'=>'required',
        ]);
    
        if ($validator->fails()) {
            throw new AuthenticationException();
        }
        
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
        
        return $this->proxy(
            $request,
            [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->input('refresh_token'),
            ],
            $proxy
        );
    }
    
    /**
     * @return array
     */
    protected function getPasswordGrandCredentials()
    {
        return ['client_id' => $this->clientId, 'client_secret' => $this->clientSecret];
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param array $parameters
     * @param \Illuminate\Http\Request $proxy
     * @return \Illuminate\Http\Response
     */
    protected function proxy(Request $request, array $parameters, Request $proxy)
    {
        $request->request->add(array_merge($parameters, $this->getPasswordGrandCredentials()));
        return \Route::dispatch($proxy);
    }
}