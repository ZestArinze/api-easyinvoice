<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSignUpRequest;
use App\Models\User;
use App\Utils\AppHttpUtils;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends Controller
{
    public function register(StoreSignUpRequest $request) {
        
        // Retrieve the validated input data
        $validated = $request->validated();

        $user = User::create($validated);

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $user);
    }
}
