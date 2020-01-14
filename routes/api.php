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

Route::group(['prefix' => 'auth', 'middleware' => ['check.headers']], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');



    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
   });
});
Route::group([], function () {
    Route::resource('users', 'Api\UserController');

    Route::group(['middleware' => ['auth:api', 'verified', 'check.headers']], function () {
        Route::get('dashboard', 'Api\UserController@dashboard');
    });
});

Route::resource('users', 'Api\UserController');
