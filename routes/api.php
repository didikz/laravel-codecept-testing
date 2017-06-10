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
Route::post('auth', 'AuthController@auth');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['jwt.auth']);
Route::post('user/articles', 'ArticleController@store')->middleware(['jwt.auth']);
