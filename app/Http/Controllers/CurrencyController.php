<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Utils\AppHttpUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    /**
     * get all currencies
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse {

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, Currency::all());
    }
}
