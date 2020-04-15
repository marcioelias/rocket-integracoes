<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::middleware(['auth:web'])->group(function() {

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/perfil', 'UserController@profile')->name('user.profile');
    Route::get('/alterar_senha', 'UserController@showChangePassword')->name('user.form.change.password');
    Route::put('/alterar_senha/{user}', 'UserController@changePassword')->name('user.change.password');

    Route::get('/json/fields', 'FieldController@getFields');
    Route::get('/json/fields/{field}', 'FieldController@getField');
    Route::get('/json/all/fields', 'FieldController@getAllFields');
    Route::get('/json/webhooks', 'WebhookController@getWebhooks');
    Route::get('/json/webhook/{webhook}', 'WebhookController@getWebhook');
    Route::get('/json/api_endpoint/{api_endpoint}', 'ApiEndpointController@getApiEndpoint');
    Route::get('/json/apis', 'ApiController@getApis');
    Route::get('/json/apis/{api}', 'ApiController@getApi');
    Route::get('/json/users/{user}', 'UserController@getUser');
    Route::get('/json/products', 'ProductController@getProducts');
    Route::get('/json/products/{product}', 'ProductController@getProduct');
    Route::get('/json/api_endpoints/api/{api}', 'ApiEndpointController@getApiEndpointsByApi');
    Route::get('/json/actions/{action}', 'ActionController@getAction');
    Route::get('/json/products/{product}/actions', 'ActionController@getActionsByProduct');
    Route::get('/json/events/product/{product}', 'EventController@getEventsByProduct');
    Route::get('/json/webhooks/{webhook}/events', 'WebhookController@getWebhookEvents');
    Route::get('/json/events/{event}', 'EventController@getEvent');
    Route::get('/json/last_api_calls', 'DashboardController@getApiLastCalls');

    Route::get('/json/graph/api_calls', 'DashboardController@getApiCallsSeries');

    Route::post('/json/actions/{action}/active/toggle', 'ActionController@toggleActive');
    Route::post('/json/users/{user}/active/toggle', 'UserController@toggleActive');
    Route::put('/json/users/{user}/change_password', 'UserController@changePassword');

    Route::get('/webhook_calls', 'WebhookCallController@index')->name('webhook_calls.index');
    Route::get('/webhook_calls/{webhook_call}', 'WebhookCallController@show')->name('webhook_calls.show');

    Route::get('/short_url_configs', 'ShortUrlConfigController@edit')->name('short_url_configs.edit');
    Route::put('/short_url_configs/{short_url_config}', 'ShortUrlConfigController@update');
    Route::get('/user/{user}/profile', 'UserController@show')->name('users.profile');
    Route::resource('/users', 'UserController');
    Route::resource('/webhooks', 'WebhookController');
    Route::resource('/apis', 'ApiController');
    Route::resource('/api_endpoints', 'ApiEndpointController');
    Route::resource('/actions', 'ActionController');
    Route::resource('/fields', 'FieldController');
    Route::resource('/products', 'ProductController');
    Route::resource('/events', 'EventController');
    Route::resource('/actions', 'ActionController');
});

Route::post('/webhook/{name}', 'WebhookController@webhook')->name('wehbook_route');
