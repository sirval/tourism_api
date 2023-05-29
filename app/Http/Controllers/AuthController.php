<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegistrationFormRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $auth_service;

     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthService $auth_service) 
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

        $this->auth_service = $auth_service;
    }

    public function login(LoginFormRequest $request): JsonResponse
    {
        return response()->json($this->auth_service->login($request));
    }

    public function register(RegistrationFormRequest $request): JsonResponse
    {
        return response()->json($this->auth_service->register($request));
    }

    public function logout(Request $request): JsonResponse
    {
        return response()->json($this->auth_service->logout($request));
    }

    public function refresh(Request $request): JsonResponse
    {
        return response()->json($this->auth_service->refresh($request));
    }
}
