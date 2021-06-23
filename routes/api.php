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

Route::group(['middleware' => 'json.response'],function () {



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

    Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1','middleware' => ['throttle:60,1']], function () {

        Route::get('/country',"WorldApiController@Country")->name('country');
        Route::get('/state',"WorldApiController@State")->name('state');
        Route::get('/city',"WorldApiController@City")->name('city');
        Route::get('/StateById',"WorldApiController@StateById")->name('StateById');
        Route::get('/CityById',"WorldApiController@CityById")->name('CityById');

        Route::get('/category',"ResourceApiController@Category")->name('category');
        Route::get('/sub-category',"ResourceApiController@SubCategory")->name('subcategory');

        Route::get('/resource',"ResourceApiController@Resources")->name('resources');



        // COVID API ROUTES
        Route::group(['prefix' => 'covid-19', 'as' => 'covid-19.'], function () {

            Route::get('all/', 'worldometersApiController@getAll')->name('all');
            Route::get('states/', 'worldometersApiController@getStates')->name('states');
            Route::get('continents/', 'worldometersApiController@getcontinents')->name('continents');
            Route::get('countries/', 'worldometersApiController@getcountries')->name('countries');

            Route::get('historical/', 'JHUCSSEApiController@historical')->name('historical');
            Route::get('historical/{country}', 'JHUCSSEApiController@historicalbyCountry')->name('historical.byCountry');

            //mobility API
             Route::group(['as' => 'mobility.'], function () {
                 Route::get('/apple', 'AppleApiController@appleCountries')->name('get');
                 Route::get('/apple/{country}/{region?}', 'AppleApiController@appleMobility')->name('data');
             });
        });

        // Gov api
        Route::group(['as' => 'gov.'], function () {

            Route::get('/gov', 'GovernmentApiController@get_Gov')->name('get');
            Route::get('/gov/{country}', 'GovernmentApiController@gov')->name('byCountry');
        });






    });
    Route::get('/ping',"Api\PingApiController@ping")->name('ping');
    Route::group(['prefix' => 'auth', 'as' => 'api.', 'namespace' => 'Api\V1','middleware' => 'throttle:15,1'], function () {

        Route::post('/register', 'ValidationApiController@register');

        Route::post('/login', "ValidationApiController@login");

        Route::post('/logout', "ValidationApiController@logout");

    });

});
// Route::fallback(function($e){
//     return response()->json(ApiHelper::SuccessorFail(404,array("error" => "route '".$e."' is not a valid check if you are using method")));
// })->name('api.fallback.404');

