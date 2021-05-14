<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Helpers\ApiHelper;

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

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1','middleware' => 'throttle:60,1'], function () {

    Route::get('/ping',"PingApiController@ping")->name('ping');
    Route::get('/country',"WorldApiController@Country")->name('country');
    Route::get('/state',"WorldApiController@State")->name('state');
    Route::get('/city',"WorldApiController@City")->name('city');

    Route::get('/category',"ResourceApiController@Category")->name('category');
    Route::get('/sub-category',"ResourceApiController@SubCategory")->name('subcategory');

    Route::get('/resource',"ResourceApiController@Resources")->name('resources');



});
Route::group(['prefix' => 'auth', 'as' => 'api.', 'namespace' => 'Api\V1','middleware' => 'throttle:60,1'], function () {

    Route::post('/register', "ApiController@register");

    Route::post('/login', "ApiController@login");

    Route::post('/logout', "ApiController@logout");

});


Route::fallback(function($e){
    return response()->json(ApiHelper::SuccessorFail(404,array("error" => "route '".$e."' not found")));
})->name('api.fallback.404');

