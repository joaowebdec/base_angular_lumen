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
    echo json_encode(['msg' => 'API do Comunica legal', 'versao' => '1.0.0']);
});

/* Rota de usuários */
$router->group(['prefix' => 'users'], function() use ($router) {

    $router->get('/', 'UsersController@index');
    $router->get('/{id}', 'UsersController@getUser');
    $router->get('/exists/email/{email}', 'UsersController@email');
    $router->post('login', 'UsersController@login');
    $router->post('/', 'UsersController@insert');
    $router->put('/{id}', 'UsersController@update');
    $router->delete('/{id}', 'UsersController@delete');
    $router->put('/{id}/restore', 'UsersController@restore');
    $router->put('/{id}/password', 'UsersController@password');

});

/* Rota de importações */
$router->group(['prefix' => 'importations'], function() use ($router) {

    $router->post('/', 'ImportationsController@import');
    $router->get('/', 'ImportationsController@listImports');
    $router->get('/banks', 'ImportationsController@getBanks');
    $router->get('/banks/{bankId}/actions', 'ImportationsController@getBankActions');
    $router->get('/banks/{bankId}/actions/{actionId}/mappings', 'ImportationsController@getBankActionMappings');

});