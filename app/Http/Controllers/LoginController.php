<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * sign-up user
     * 
     * @param LoginRequest $loginRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $loginRequest): JsonResponse {

        // handles login and issuing of auth token
        $service = new LoginService($loginRequest);
        
        return $service->loginHandler();
    }
}
