<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/product', 'UserProductController@index');
Route::get('/user/product/{product}', 'UserProductController@show');
Route::put('/user/product/{product}', 'UserProductController@associateProduct');
Route::delete('/user/product/{product}', 'UserProductController@dissociateProduct');

Route::post('/product', 'ProductController@store');
Route::get('/product', 'ProductController@index');
Route::get('/product/{product}', 'ProductController@show');
Route::put('/product/{product}', 'ProductController@update');
Route::patch('/product/{product}', 'ProductController@update');
Route::delete('/product/{product}', 'ProductController@destroy');

Route::put('/product/{product}/image', 'ProductController@image');
