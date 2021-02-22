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

// Registrar un participante
$router->post('/registrar_participante', 'ParticipanteController@registrar_participante');
$router->post('/obtener_participantes', 'ParticipanteController@obtener_participantes');
$router->post('/detalle_participante', 'ParticipanteController@detalle_participante');
$router->post('/editar_participante', 'ParticipanteController@editar_participante');
$router->post('/eliminar_participante', 'ParticipanteController@eliminar_participante');
$router->post('/actualizar_pass', 'UsuarioController@actualizar_pass');

$router->post('/obtener_roles', 'UsuarioController@obtener_roles');

$router->get('/test_pdf', 'PDFController@test');

$router->post('/registrar_reunion', 'ReunionController@registrar_reunion');
$router->post('/obtener_reuniones', 'ReunionController@obtener_reuniones');
$router->post('/detalle_reunion', 'ReunionController@obtener_detalle');
$router->post('/eliminar_reunion', 'ReunionController@eliminar_reunion');
$router->post('/editar_reunion', 'ReunionController@editar_reunion');

//Compartir la bitácora de la reunión
$router->post('/compartir_bitacora', 'CompartirController@compartir_bitacora');
$router->post('/historial_envios', 'CompartirController@historial_envios');

// Registrar en el calendario
$router->post('/registrar_calendario', 'CalendarioController@registrar_calendario');
$router->post('/obtener_calendario', 'CalendarioController@obtener_calendario');
$router->post('/editar_calendario', 'CalendarioController@editar_calendario');
$router->post('/eliminar_evento', 'CalendarioController@eliminar_evento');

$router->get('/test_mail', 'CompartirController@test_mail');

// Subir avatar
$router->post('/subir_avatar', 'ParticipanteController@subir_avatar');
$router->post('/editar_avatar', 'ParticipanteController@editar_avatar');

