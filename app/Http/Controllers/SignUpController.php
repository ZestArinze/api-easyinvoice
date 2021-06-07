<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignUpRequest;
use App\Models\User;
use App\Utils\AppHttpUtils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends Controller
{
    /**
     * sign-up user
     * 
     * @param StoreSignUpRequest $storeSignUpRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function register(StoreSignUpRequest $storeSignUpRequest): JsonResponse {
        
        // Retrieve the validated input data
        $validated = $storeSignUpRequest->validated();

        $user = User::create(array_merge(
            $validated, 
            ['password' => bcrypt($validated['password'])])
        );

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_CREATED, $user);
    }
}
