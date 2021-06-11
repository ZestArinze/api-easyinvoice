<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessUser;
use Illuminate\Http\Request;

class BusinessService {

    public function associateBusinessWithUser(Business $business) {

        // @TODO this is a workaround for dabatase seeder
        if(!auth()->id()) return;

        $businessUser = new BusinessUser();
        $businessUser->user_id = auth()->id();
        $businessUser->business_id = $business->id;
        $businessUser->save();
    }
    

    /**
     * 
     * generate some unique random string as business ID
     */
    public static function generateUniqueId(): string {
        // get max ID in the table
        $maxId = Business::max('id') + 1;
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

    public function overview(Request $request) {

        $clientService = new ClientService();        

        $user = $request->user();
        $businessCount = $user->businesses()->count();

        $clientsCount = $clientService->getClients($request, true);

        return [
            'user' => $user,
            'business_count' => $businessCount,
            'client_count' => $clientsCount,
        ];
    }
}