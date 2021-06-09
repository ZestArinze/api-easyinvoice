<?php

namespace App\Services;

use App\Http\Requests\StoreClientRequest;
use App\Models\BusinessUser;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Utils\AppHttpUtils;
use App\Utils\DataUtils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientService {

    public function associateClientWithInvoice() {
        $businessInvoice = new ClientInvoice();
        $businessInvoice->user_id = auth()->id();
        $businessInvoice->business_id = $this->business->id;
        $businessInvoice->save();
    }

    /**
     * 
     * generate some unique random string as business ID
     */
    public static function generateUniqueId(): string {
        // get max ID in the table
        $maxId = '' . Client::max('id');
        $businessIdLen = 12;
        $businessId = '';

        for($i = 0; $i < $businessIdLen; $i++) {
            $fullId = $businessId . $maxId;
            if(strlen($fullId) >= $businessIdLen) {
                $businessId = $fullId;
                break;
            }
            $businessId .= rand(0, 9);
        }

        return $businessId;
    }

    /**
     * create client. obeserver associates client to
     * the authenticated user
     * 
     * @param StoreClientRequest $storeClientRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function createClient(StoreClientRequest $storeClientRequest): JsonResponse {
        // user must select a business the they belong to
        $business = BusinessUser::userBusiness($storeClientRequest->business_id);
        if(!$business) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_NOT_FOUND, null, null, 'Invalid business ID.');
        }

        // client should be identified by unique email???
        $existingClient = Client::clientRecord($storeClientRequest->email, $business->id);
        if($existingClient) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_BAD_REQUEST, $existingClient, null, 'You already have a client with that email.');
        }

        $validated = $storeClientRequest->validated();
        $client = new Client($validated);
        $client->business_id = $business->id;
        $client->save();

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_CREATED, $client, null, 'Client created.');
    }

    
}