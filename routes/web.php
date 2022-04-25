<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();
});
$router->group(['prefix'=>'api'],function () use ($router){
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/user','TodoController@getuser');
        $router->get('/todos','TodoController@index');
        $router->post('/todo', 'TodoController@store');
        $router->put('/todo/{id}', 'TodoController@update');
        $router->delete('/todo/{id}', 'TodoController@destroy');
        $router->post('/logout', 'AuthController@logout');
    });
});