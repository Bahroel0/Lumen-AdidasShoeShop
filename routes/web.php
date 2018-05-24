<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    $res['success'] = true;
    $res['result'] = "Welcome to AdidasShoeShop services !";
    return response($res);
});

$router->post('/api/login', 'LoginController@index');
$router->post('/api/register', 'UserController@register');
$router->post('/api/update','UserController@changePassword');
$router->get('/api/user/{id}', ['middleware' => 'auth', 'uses' =>  'UserController@getUser']);
