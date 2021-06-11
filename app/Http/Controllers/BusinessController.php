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
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function overview(Request $request): JsonResponse {
        
        $user = $request->user();
        // $businessCount = $user->businesses()->get();
        $businessCount = $user->businesses()->count();

        $data = [
            'user' => $user,
            'business_count' => $businessCount,
        ];

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $data);
    }

    /**
     * get businesses the user belongs to
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse {
        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $request->user()->businesses);
    }
}
