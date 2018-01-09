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
        $api->group(['prefix' => 'weapp-login'], function (RouteRegisterContract $api) {

            // @POST /api/v2/weapp-login/login-code
            $api->post('/login-code', API\LoginController::class . '@loginByCode');
            // @POST /api/v2/weapp-login/user-setinfo
            $api->post('/user-setinfo', API\UserController::class . '@SetUserInfo');
        });
    });
});
