<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    Route::group([
        'middleware' => 'auth:api'
      ], function() {
            Route::apiResource('customers', 'CustomerController');

            Route::group(['prefix' => 'customers'], function () {
                Route::get('/{id}/orders', [
                    'uses' => 'CustomerController@order',
                    'as' => 'customers.orders',
                ]);

                Route::post('/{customer_id}/orders/{order_id}', [
                    'uses' => 'CustomerController@order',
                    'as' => 'orders.details',
                ]);

                Route::post('/{id}/orders', [
                    'uses' => 'CustomerController@order',
                    'as' => 'customers.orders',
                ]);
            });

            Route::apiResource('inventories', 'InventoryController');
            Route::apiResource('orders', 'OrderController');
    });
});


