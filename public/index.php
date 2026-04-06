<?php
session_start();

spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/../app/core/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$router = new Router();

// ============= RUTAS =============
$router->add('GET', '', 'HomeController', 'index');

// Autenticación
$router->add('GET', 'login', 'AuthController', 'showLogin');
$router->add('POST', 'login', 'AuthController', 'login');
$router->add('GET', 'register', 'AuthController', 'showRegister');
$router->add('POST', 'register', 'AuthController', 'register');
$router->add('GET', 'logout', 'AuthController', 'logout');

// Dashboard
$router->add('GET', 'dashboard', 'DashboardController', 'index');

// ============= RUTAS DE CLIENTE (INCIDENCIAS) =============
$router->add('GET', 'my-incidents', 'IncidentController', 'myIncidents');
$router->add('GET', 'new-incident', 'IncidentController', 'newIncident');
$router->add('POST', 'new-incident', 'IncidentController', 'createIncident');
$router->add('POST', 'cancel-incident', 'IncidentController', 'cancelIncident');

// ============= PROCESAR PETICIÓN =============
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace('/index.php', '', $uri);
$uri = ltrim($uri, '/');

$router->dispatch($method, $uri);