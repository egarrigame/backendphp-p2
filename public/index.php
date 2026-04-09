<?php
session_start(); // motor de sesión
require_once '../src/config/database.php'; // Configuración de la bbdd
require_once '../src/core/Router.php'; // Clase router 
$db = conectarDB();

$router = new Router(); // Instancia del Router

// REGISTRO DE RUTAS
$router->get('/', 'HomeController', 'index');
$router->get('/registro', 'AuthController', 'mostrarRegistro');
$router->post('/registro', 'AuthController', 'procesarRegistro');
$router->get('/login', 'AuthController', 'mostrarLogin');
$router->post('/login', 'AuthController', 'procesarLogin');
$router->get('/logout', 'AuthController', 'cerrarSesion');

$router->get('/panel', 'DashboardController', 'index');
$router->post('/nueva-incidencia', 'IncidenciaController', 'guardar');

$router->despachar(); // Que Router ea la URL y ejecute lo necesario