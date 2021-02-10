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
    return $router->app->version();
});

$router->get('/prueba', 'UsuarioController@prueba');

$router->post('/registrar_usuario', 'UsuarioController@registrar_usuario');
$router->post('/login', 'UsuarioController@login');
$router->post('/datos_usuario', 'UsuarioController@datos_usuario');

// Obtener las personas para compartir 
$router->post('/personas_compartir', 'PersonaController@personas_compartir');

$router->post('/generar_vistaprevia', 'PDFController@generar_vistaprevia');

