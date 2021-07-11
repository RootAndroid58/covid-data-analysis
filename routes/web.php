<?php

use App\Models\Country;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * Social Login Routes
 */
Route::get('login/{provider}', 'SocialController@redirect');
Route::get('login/{provider}/callback','SocialController@Callback');






// Route::redirect('/', '/login');
Route::get('/', 'HomeController@index')->name('homepage');
Route::get('/apple', 'HomeController@trends')->name('apple_trends');
Route::get('/worldometer', 'HomeController@worldometer')->name('worldometer');
Route::get('/apple/{search}', 'HomeController@country')->name('apple_trends.search');
Route::get('status','HomeController@status')->name('status');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
})->name('home');

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', '2fa']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::post('categories/media', 'CategoriesController@storeMedia')->name('categories.storeMedia');
    Route::post('categories/ckmedia', 'CategoriesController@storeCKEditorImages')->name('categories.storeCKEditorImages');
    Route::post('categories/parse-csv-import', 'CategoriesController@parseCsvImport')->name('categories.parseCsvImport');
    Route::post('categories/process-csv-import', 'CategoriesController@processCsvImport')->name('categories.processCsvImport');
    Route::resource('categories', 'CategoriesController');

    // Resources
    Route::delete('resources/destroy', 'ResourcesController@massDestroy')->name('resources.massDestroy');
    Route::resource('resources', 'ResourcesController');

    // City
    Route::delete('cities/destroy', 'CityController@massDestroy')->name('cities.massDestroy');
    Route::post('cities/parse-csv-import', 'CityController@parseCsvImport')->name('cities.parseCsvImport');
    Route::post('cities/process-csv-import', 'CityController@processCsvImport')->name('cities.processCsvImport');
    Route::resource('cities', 'CityController');

    // State
    Route::delete('states/destroy', 'StateController@massDestroy')->name('states.massDestroy');
    Route::post('states/parse-csv-import', 'StateController@parseCsvImport')->name('states.parseCsvImport');
    Route::post('states/process-csv-import', 'StateController@processCsvImport')->name('states.processCsvImport');
    Route::resource('states', 'StateController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::post('countries/parse-csv-import', 'CountriesController@parseCsvImport')->name('countries.parseCsvImport');
    Route::post('countries/process-csv-import', 'CountriesController@processCsvImport')->name('countries.processCsvImport');
    Route::resource('countries', 'CountriesController');

    // New Req
    Route::delete('new-reqs/destroy', 'NewReqController@massDestroy')->name('new-reqs.massDestroy');
    Route::post('new-reqs/media', 'NewReqController@storeMedia')->name('new-reqs.storeMedia');
    Route::post('new-reqs/ckmedia', 'NewReqController@storeCKEditorImages')->name('new-reqs.storeCKEditorImages');
    Route::resource('new-reqs', 'NewReqController');

    // Sub Categories
    Route::delete('sub-categories/destroy', 'SubCategoriesController@massDestroy')->name('sub-categories.massDestroy');
    Route::post('sub-categories/parse-csv-import', 'SubCategoriesController@parseCsvImport')->name('sub-categories.parseCsvImport');
    Route::post('sub-categories/process-csv-import', 'SubCategoriesController@processCsvImport')->name('sub-categories.processCsvImport');
    Route::resource('sub-categories', 'SubCategoriesController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    Route::group(['prefix' => 'application', 'as' => 'app.'],function () {

        Route::get('','ApplicationController@index')->name('index');
        Route::get('clear', 'ApplicationController@ClearCache')->name('cache');
        Route::get('clear/audit', 'ApplicationController@ClearAudit')->name('audit');
        Route::get('clear/media', 'ApplicationController@cleanMedia')->name('media');
        Route::get('clear/token', 'ApplicationController@clearTokens')->name('token');
        Route::get('clear/all-cache', 'ApplicationController@clearAllCache')->name('all-cache');
        Route::get('linkstorage', 'ApplicationController@linkStorage')->name('sys-link');
        Route::get('scraper/start', 'ApplicationController@scraperStart')->name('scraper');
        Route::get('scraper/start/covid', 'ApplicationController@scraperStartCovid')->name('covid-scrapper');
        Route::get('run', 'ApplicationController@covidCommands')->name('covid-commands');
        Route::get('site/down', 'ApplicationController@startMaintainance')->name('down');
        Route::get('site/up', 'ApplicationController@stopMaintainance')->name('up');
    });


});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
        Route::post('profile/two-factor', 'ChangePasswordController@toggleTwoFactor')->name('password.toggleTwoFactor');
        Route::post('profile/token', 'ChangePasswordController@genToken')->name('password.createToken');
        Route::post('profile/token/del', 'ChangePasswordController@removeToken')->name('password.removeToken');
        Route::get('system-call','ChangePasswordController@system_call')->middleware('can:user_management_access')->name('call');
    }
});
Route::group(['namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Two Factor Authentication
    if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
        Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
        Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
        Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
    }
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('auth','can:log_viewer');

Route::group(['prefix'=>'api'] ,function () {
    Route::get('/', function () {
        return view('doc.api');
    })->name('apidoc');
});



Route::group(['as' => 'web.','namespace' => 'Website'],function () {
    Route::get('/privacy-policy','PolicyController@PrivacyPolicy')->name('privacy_policy');
    Route::get('/terms_of_service','PolicyController@termsofService')->name('tos');
});




Route::get('testMail','TestController@testMail');
Route::get('/test', 'TestController@index')->name('test');
Route::get('/test1', 'TestController@test2')->name('test1');
Route::get('/test2', 'TestController@throwerror')->name('test2');
