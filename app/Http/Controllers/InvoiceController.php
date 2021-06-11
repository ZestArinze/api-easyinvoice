<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Utils\AppHttpUtils;
use App\Utils\DbUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    /**
     * create invoice. obeserver associates invoice to
     * the authenticated user
     * 
     * @param StoreInvoiceRequest $storeInvoiceRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(StoreInvoiceRequest $storeInvoiceRequest): JsonResponse {
        $invoiceService = new InvoiceService();

        return $invoiceService->createInvoice($storeInvoiceRequest);
    }

    /**
     * update invoice.
     * 
     * @param StoreInvoiceRequest $storeInvoiceRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function update(UpdateInvoiceRequest $updateInvoiceRequest): JsonResponse {
        $invoiceService = new InvoiceService();
        
        return $invoiceService->updateInvoice($updateInvoiceRequest);
    }

    /**
     * search invoices
     * 
     * @param SearchInvoiceRequest $searchInvoiceRequest request
     * @return Illuminate\Http\JsonResponse
     */
    public function search(SearchInvoiceRequest $searchInvoiceRequest): JsonResponse {
        $invoiceService = new InvoiceService();

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $invoiceService->searchInvoices($searchInvoiceRequest));
    }

    /**
     * get invoices for businesses the user belongs to
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse {
        $invoiceService = new InvoiceService();

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $invoiceService->getInvoices($request));
    }

    /**
     * get sinegle invoice for the business the user belongs to
     * 
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id): JsonResponse {
        $invoiceService = new InvoiceService();

        return AppHttpUtils::appJsonResponse(true, Response::HTTP_OK, $invoiceService->getInvoices($request, $id));
    }
}
