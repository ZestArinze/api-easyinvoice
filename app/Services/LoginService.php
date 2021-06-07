<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Utils\AppHttpUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginService {

    private LoginRequest $request;

    public function __construct(LoginRequest $loginRequest)
    {
        $this->request = $loginRequest;
    }

    /**
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function loginHandler(): JsonResponse {

        // extract items from request payload
       $user = User::where('email', $this->request->email)->first();
       if(!$user) {
        return AppHttpUtils::appJsonResponse(
            true, 
            Response::HTTP_OK, 
            null,
            null,
            'Invalid Credentials.');
       }

        if (Hash::check($this->request->password, $user->password)) {
            $tokenResult = $user->createToken('Personal Access Client');
            $responseData = [
                'access_token' => $tokenResult->plainTextToken,
                'token_type' => 'Bearer',
                'user' => $user,
            ];

            return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $responseData);
        }

        return AppHttpUtils::appJsonResponse(
            true, 
            Response::HTTP_OK, 
            null,
            null,
            'Invalid Credentials.');
    }
}