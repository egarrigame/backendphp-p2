<?php
require_once '../src/config/database.php'; // Configuración de la bbdd
require_once '../src/core/Router.php'; // Clase router 
$db = conectarDB();
echo "Conexión con la bbdd desde Docker";

$router = new Router(); // Instancia del Router

// REGISTRO DE RUTAS
$router->get('/', 'HomeController', 'index');
$router->get('/dodo', 'PruebaController', 'metodoDodo');
$router->get('/inventada', 'ErrorController', 'noExiste');

$router->despachar(); // Que Router ea la URL y ejecute lo necesario