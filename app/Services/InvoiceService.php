<?php

namespace App\Services;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Business;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Utils\AppHttpUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvoiceService {

    /**
     * 
     * generate some unique random string as business ID
     */
    public static function generateUniqueId(): string {
        // get max ID in the table
        $maxId = Invoice::max('id') + 1;
        $invoiceIdLen = 12;
        $invoiceNumber = 'INV-';

        for($i = 0; $i < $invoiceIdLen; $i++) {
            $fullId = $invoiceNumber . $maxId;
            if(strlen($fullId) >= $invoiceIdLen) {
                $invoiceNumber = $fullId;
                break;
            }
            $invoiceNumber .= '0';
        }

        return $invoiceNumber;
    }

    /**
     * create invoice
     * 
     * @param StoreInvoiceRequest $storeInvoiceRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function createInvoice(StoreInvoiceRequest $storeInvoiceRequest): JsonResponse {
        // client exists since validation ensured that
        $client = Client::find($storeInvoiceRequest->client_id);
        $business = $client->business;
        if(!$business || !$business->hasUser()) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_NOT_FOUND, null, null, 'No such client for your business.');
        }

        $invoice = new Invoice();
        $invoiceItems = [];
        
        DB::beginTransaction();
        try {
            $subTotal = 0;
            $items = $storeInvoiceRequest->invoice_items;

            for($i = 0; $i < count($items); $i++) {
                $item = $items[$i];
                $amount = $item['unit_price'] * $item['quantity'];
                $subTotal += ($item['unit_price'] * $item['quantity']);

                $invoiceItem = new InvoiceItem($item);
                $invoiceItem->amount = $amount;
                $invoiceItems[] = $invoiceItem;
            }

            $invoice = $this->getInvoiceInstance($storeInvoiceRequest, $client, $business, $subTotal);
            $invoice->save();

            for($i = 0; $i < count($items); $i++) {
                $invoiceItem = $invoiceItems[$i];
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->save();
            }

            DB::commit();
        } catch(Throwable $e) {
            DB::rollBack();
        }

        $invoice->items = $invoiceItems;
        
        return AppHttpUtils::appJsonResponse(true, Response::HTTP_CREATED, $invoice, null, 'Invoice created.');
    }

    /**
     * update invoice
     * 
     * @param UpdateInvoiceRequest $updateInvoiceRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function updateInvoice(UpdateInvoiceRequest $updateInvoiceRequest): JsonResponse {
        $client = Client::find($updateInvoiceRequest->client_id);
        $business = $client->business;
        if(!$business || !$business->hasUser()) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_NOT_FOUND, null, null, 'No such client for your business.');
        }

        $invoice = Invoice::where([
            'id' => $updateInvoiceRequest->invoice_id,
            'client_id' => $client->id,
        ])->first();
        if(!$invoice) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_NOT_FOUND, null, null, 'No such invoice for your business.');
        }

        if($invoice->status == Invoice::STATUS_ISSUED) {
            return AppHttpUtils::appJsonResponse(false, Response::HTTP_BAD_REQUEST, null, null, 'This invoice has already been issued to the client.');
        }
        
        DB::beginTransaction();
        try {
            $subTotal = 0;
            $items = $updateInvoiceRequest->invoice_items;

            for($i = 0; $i < count($items); $i++) {
                $item = $items[$i];
                $amount = $item['unit_price'] * $item['quantity'];
                $subTotal += ($item['unit_price'] * $item['quantity']);

                $invoiceItem = new InvoiceItem($item);
                $invoiceItem->amount = $amount;
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->save();
            }

            $invoice->subtotal = $subTotal;
            $invoice->total = $subTotal - ($subTotal * ($updateInvoiceRequest->vat ?? $invoice->vat) * 0.01);
            $invoice->save();

            DB::commit();
        } catch(Throwable $e) {
            DB::rollBack();
        }
        
        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $invoice, null, 'Invoice updated.');
    }

    private function getInvoiceInstance(Request $request, Client $client, Business $business, $subTotal): Invoice {
        
        $validated = $request->validated();

        $invoice = new Invoice($validated);
        $invoice->client_id = $client->id;
        $invoice->business_id = $business->id;
        $invoice->invoice_number = InvoiceService::generateUniqueId();
        $invoice->subtotal = $subTotal;
        $invoice->total = $subTotal - ($subTotal * ($request->vat) * 0.01);

        return $invoice;
    }

    public static function getInvoiceSubTotal(StoreInvoiceRequest $storeInvoiceRequest): float {
        
        $total = 0;

        $items = $storeInvoiceRequest->invoice_items;
        for($i = 0; $i < count($items); $i++) {
            $item = $items[$i];

            $total += ($item['unit_price'] * $item['quantity']); 
        }

        return $total;
    }
}