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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Get User
Route::get('/users/{role}', ['as' => 'users.index', 'uses' => 'API\UserController@index']);
Route::get('/users/show/{id}', ['as' => 'users.show', 'uses' => 'API\UserController@show']);
