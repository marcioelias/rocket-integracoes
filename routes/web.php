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

//Route::view('teste', 'teste');

Route::view('eventos', 'teste');

Route::get('teste', function() {
    $nomeCompleto = 'Márcio Elias Hahn do Nascimento';
    $variavel = 'VARIAVEL';

    $txt = 'Esse é o código do @firstName({{ nome }}) e essa é uma {{ variavel }}';


    $var = 'nomeCompleto';
    //$variaveisRegex = '~\{\{(\s\b'.$var.'\b\s)\}\}~';
    $variaveisRegex = '~\{\{([\s\w]*)\}\}~';
    $firstNameRegex = '~\@(\bfirstName\(\b)([(\s\w"{})]*)(\))~';

    $txt1 = preg_replace_callback($variaveisRegex, function($match) {
        return strtoupper($match[1]);
    }, $txt);

    return View('teste')->withTexto($txt1);

    $txt = preg_replace_callback($firstNameRegex, function($match) {
        return explode(' ', $match[2])[0];
    }, $txt);


});

Route::middleware(['auth:web'])->group(function() {

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/perfil', 'UserController@profile')->name('user.profile');
    Route::get('/alterar_senha', 'UserController@showChangePassword')->name('user.form.change.password');
    Route::put('/alterar_senha/{user}', 'UserController@changePassword')->name('user.change.password');

    Route::get('/json/fields', 'FieldController@getFields');
    Route::get('/json/fields/{field}', 'FieldController@getField');
    Route::get('/json/fields/all', 'FieldController@getAllFields');
    Route::get('/json/webhooks', 'WebhookController@getWebhooks');
    Route::get('/json/webhook/{webhook}', 'WebhookController@getWebhook');
    Route::get('/json/api_endpoint/{api_endpoint}', 'ApiEndpointController@getApiEndpoint');
    Route::get('/json/apis', 'ApiController@getApis');
    Route::get('/json/apis/{api}', 'ApiController@getApi');
    Route::get('/json/products', 'ProductController@getProducts');
    Route::get('/json/products/{product}', 'ProductController@getProduct');
    Route::get('/json/api_endpoints/api/{api}', 'ApiEndpointController@getApiEndpointsByApi');
    Route::get('/json/actions/{action}', 'ActionController@getAction');
    Route::get('/json/events/product/{product}', 'EventController@getEventsByProduct');
    Route::get('/json/events/{event}', 'EventController@getEvent');

    Route::post('/json/actions/{action}/active/toggle', 'ActionController@toggleActive');

    Route::resource('/users', 'UserController');
    Route::resource('/webhooks', 'WebhookController');
    Route::resource('/apis', 'ApiController');
    Route::resource('/api_endpoints', 'ApiEndpointController');
    Route::resource('/integrations', 'IntegrationController');
    Route::resource('/actions', 'ActionController');
    Route::resource('/fields', 'FieldController');
    Route::resource('/products', 'ProductController');
    Route::resource('/events', 'EventController');
    Route::resource('/actions', 'ActionController');
});

Route::post('/webhook/{name}', 'WebhookController@webhook')->name('wehbook_route');
