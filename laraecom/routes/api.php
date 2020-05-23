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
Route::get('sizes', 'SizeController@index');
Route::get('sizes/{size}', 'SizeController@show');
Route::get('colors', 'ColorController@index');
Route::get('colors/{color}', 'ColorController@show');
Route::get('categories', 'CategoryController@index');
Route::get('categories/{category}', 'CategoryController@show');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'AuthController@logout');

    Route::post('sizes', 'SizeController@store');
    Route::put('sizes/{size}', 'SizeController@update');
    Route::delete('sizes/{size}', 'SizeController@destroy');

    Route::post('colors', 'ColorController@store');
    Route::put('colors/{color}', 'ColorController@update');
    Route::delete('colors/{color}', 'ColorController@destroy');

    Route::get('users', 'UserController@index');
    Route::get('users/{user}', 'UserController@show');
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@destroy');

    Route::post('categories', 'CategoryController@store');
    Route::put('categories/{category}', 'CategoryController@update');
    Route::delete('categories/{category}', 'CategoryController@destroy');

    Route::post('products', 'ProductController@store');
    Route::put('products/{product}', 'ProductController@update');
    Route::delete('products/{product}', 'ProductController@destroy');
});

