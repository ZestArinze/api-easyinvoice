<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ClientController;
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
	Route::post('businesses', [BusinessController::class, 'store']);
    Route::get('businesses/overview', [BusinessController::class, 'show']);

	Route::post('clients', [ClientController::class, 'store']);
	Route::post('clients/search', [ClientController::class, 'search']);

});