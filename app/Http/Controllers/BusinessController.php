<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Models\Business;
use App\Models\BusinessUser;
use App\Services\BusinessService;
use App\Utils\AppHttpUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BusinessController extends Controller
{
    /**
     * create business. obeserver associates business to
     * the authenticated user
     * 
     * @param StoreBusinessRequest $storeBusinessRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(StoreBusinessRequest $storeBusinessRequest): JsonResponse {
        // Retrieve the validated input data
        $validated = $storeBusinessRequest->validated();

        $business = new Business($validated);
        $business->business_id = BusinessService::generateUniqueId();
        $business->save();

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_CREATED, $business);
    }

    /**
     * get business and associated data
     * 
     * @param App\Models\User $user request
     * @param  string  $id
     * @return Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse {
        
        $data = [
            'user' => $request->user(),
            'businesses' => BusinessUser::where('user_id', $request->user()->id)->count(),
        ];

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $data);
    }
}
