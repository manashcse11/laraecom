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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::get('variations', 'VariationController@index');
Route::get('variations/{variation}', 'VariationController@show');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'AuthController@logout');

    Route::post('variations', 'VariationController@store');
    Route::put('variations/{variation}', 'VariationController@update');
    Route::delete('variations/{variation}', 'VariationController@destroy');

    Route::get('users', 'UserController@index');
    Route::get('users/{user}', 'UserController@show');
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@destroy');
});

