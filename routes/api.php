<?php

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

