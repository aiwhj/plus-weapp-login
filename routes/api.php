<?php

use Aiwhj\WeappLogin\API\Controllers as API;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;
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
    'prefix' => 'api/v2',
], function (RouteRegisterContract $api) {

    // User check-in ranks.
    // @Route /api/v2/checkin-ranks
    Route::middleware('auth:api')->group(function () use ($api) {
        $api->group(['prefix' => 'user-setinfo'], function (RouteRegisterContract $api) {

            // Get all users check-in ranks.
            // @GET /api/v2/weapp-login
            $api->post('/', API\UserController::class . '@SetUserInfo');
        });
    });
    $api->group(['prefix' => 'login-code'], function (RouteRegisterContract $api) {

        // Get all users check-in ranks.
        // @GET /api/v2/weapp-login
        $api->post('/', API\LoginController::class . '@loginByCode');
    });
});
