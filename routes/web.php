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


// user endpoint
$router->post('/api/login', 'UserController@login');
$router->post('/api/register', 'UserController@register');
$router->post('/api/update',['middleware' => 'auth', 'uses' =>  'UserController@changePassword']);
$router->post('/api/logout',['middleware' => 'auth', 'uses' =>  'UserController@logout']);
$router->get('/api/user/{id}', ['middleware' => 'auth', 'uses' =>  'UserController@getUser']);


// produk endpoint
$router->post('/api/addProduk', 'ProdukController@addProduk');