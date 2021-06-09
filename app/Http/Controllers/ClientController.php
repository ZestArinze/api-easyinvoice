<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Services\ClientService;
use App\Utils\AppHttpUtils;
use App\Utils\DbUtils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * create client. obeserver associates client to
     * the authenticated user
     * 
     * @param StoreClientRequest $storeClientRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(StoreClientRequest $storeClientRequest): JsonResponse {
        $clientService = new ClientService();

        return $clientService->createClient($storeClientRequest);
    }

    /**
     * search clients
     * 
     * @param SearchClientRequest $searchClientRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function search(SearchClientRequest $searchClientRequest): JsonResponse {
        $clients = Client::where(DbUtils::sqlLikeQuery($searchClientRequest->validated()))->get();

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $clients);
    }
}
