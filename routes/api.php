<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
	'prefix' => 'auth'
], function() {
	Route::post('signup', [SignUpController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
});


Route::group([
	'middleware' => 'auth:sanctum'
], function() {
	Route::get('currencies', [CurrencyController::class, 'index']);

	Route::post('businesses', [BusinessController::class, 'store']);
	Route::get('businesses', [BusinessController::class, 'index']);
    Route::get('businesses/overview', [BusinessController::class, 'overview']);

	Route::post('clients', [ClientController::class, 'store']);
	Route::post('clients/search', [ClientController::class, 'search']);
	Route::get('clients', [ClientController::class, 'index']);

	Route::post('invoices', [InvoiceController::class, 'store']);
	Route::patch('invoices', [InvoiceController::class, 'update']);
	Route::post('invoices/search', [InvoiceController::class, 'search']);
	Route::get('invoices', [InvoiceController::class, 'index']);
	Route::get('invoices/{id}', [InvoiceController::class, 'show']);
});