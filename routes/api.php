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

Route::middleware('api')->get('/hello_world', function () {
    return json_encode(['message' => 'hello world']);
});

Route::group(['middleware' => ['api','cors']], function () {
    Route::post('auth/register', 'Auth\ApiRegisterController@register');
});
