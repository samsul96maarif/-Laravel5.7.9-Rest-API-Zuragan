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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group([
  'middleware' => 'auth:api',
], function() {
  Route::group([
    'prefix' => 'admin'
  ], function(){
    // store
      Route::post('/subscription/store', 'AdminSubscriptionController@store');
      // update
      Route::put('/subscription/{id}', 'AdminSubscriptionController@update');
      // delete
      Route::delete('/subscription/{id}/delete', 'AdminSubscriptionController@delete');

      // store
      // belum di config
      Route::get('/organization', 'AdminOrganizationController@index');
  });

  // subscriptionRoute::post('/subscription/store', 'SubscriptionController@store');
  Route::get('/subscription', 'SubscriptionController@index');
  // detail
  Route::get('/subscription/{id}', 'SubscriptionController@show');
  // untuk membeli dan membuat payment
  Route::post('/subscription/{id}/cart', 'SubscriptionController@buy');
  // upload bukti transfer
  Route::get('/subscription/{id}/buy/proof', 'SubscriptionController@uploadProof');
  Route::get('/subscription/{id}/extend/proof', 'SubscriptionController@uploadProof');
  // store hasil upload bukti transfer
  Route::post('/subscription/{id}/buy/proof', 'SubscriptionController@storeProof');
  // untuk masuk link upload proof dan lihat detail
  Route::get('subscription/cart', 'SubscriptionController@cart');
  // unutk melihat keranjang
  Route::get('/subscription/payment/proof', 'SubscriptionController@cart');
  // end subscription

  // create store
  Route::post('/organization/store', 'OrganizationController@store');
  // update store
  Route::put('/organization/{id}', 'OrganizationController@update');
  //detail
  Route::get('/organization/{id}', 'OrganizationController@show');
  // fungsi ini belum digunakan
  // //delete
  // Route::delete('/organization/{id}/delete', 'OrganizationController@delete');
  // end store
});
