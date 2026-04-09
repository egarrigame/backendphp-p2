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
// ============= RUTAS DE ADMINISTRADOR =============
$router->add('GET', 'admin/dashboard', 'AdminController', 'dashboard');
$router->add('GET', 'admin/tecnicos', 'AdminController', 'tecnicos');
$router->add('POST', 'admin/tecnicos/create', 'AdminController', 'createTecnico');
$router->add('POST', 'admin/tecnicos/update', 'AdminController', 'updateTecnico');
$router->add('POST', 'admin/tecnicos/delete', 'AdminController', 'deleteTecnico');
$router->add('GET', 'admin/incidencias', 'AdminController', 'incidencias');
$router->add('POST', 'admin/incidencias/update', 'AdminController', 'updateIncident');
$router->add('POST', 'admin/incidencias/cancel', 'AdminController', 'cancelIncident');
$router->add('POST', 'admin/incidencias/assign', 'AdminController', 'assignTechnician');
$router->add('GET', 'admin/calendar', 'AdminController', 'calendar');
// ============= RUTAS DE PERFIL =============
$router->add('GET', 'profile', 'UserController', 'profile');
$router->add('POST', 'profile', 'UserController', 'updateProfile');
$router->add('GET', 'change-password', 'UserController', 'showChangePassword');
$router->add('POST', 'change-password', 'UserController', 'changePassword');
// ============= PROCESAR PETICIÓN =============
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
// Eliminar parámetros GET (todo lo que viene después de ?)
$uri = strtok($requestUri, '?');
$uri = str_replace('/index.php', '', $uri);
$uri = ltrim($uri, '/');
$router->dispatch($method, $uri);