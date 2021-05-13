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

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Categories
    Route::post('categories/media', 'CategoriesApiController@storeMedia')->name('categories.storeMedia');
    Route::apiResource('categories', 'CategoriesApiController');

    // Resources
    Route::apiResource('resources', 'ResourcesApiController');

    // City
    Route::apiResource('cities', 'CityApiController');

    // State
    Route::apiResource('states', 'StateApiController');

    Route::get('/states/all',"StateApiController@index");

    // Countries
    Route::apiResource('countries', 'CountriesApiController');

    // New Req
    Route::apiResource('new-reqs', 'NewReqApiController');
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1'], function () {
    // Permissions
    Route::post('/getstatebyid', 'ResourceApiController@getStateById')->name('StateById');
    Route::post('/getCityById', 'ResourceApiController@getCityById')->name('CityById');

    Route::get('/ping',"PingApiController@ping")->name('ping');
});

