<?php
// src/core/Router.php

class Router {
    private $rutas = [ // array para almacenar las rutas
        'GET' => [],
        'POST' => []
    ];

    public function get($url, $controlador, $metodo) { // método para registrar ruta de url
        $this->rutas['GET'][$url] = [
            'controlador' => $controlador, 
            'metodo' => $metodo
        ];
    }

    public function post($url, $controlador, $metodo) { // método para registrar ruta de formulario
        $this->rutas['POST'][$url] = [
            'controlador' => $controlador, 
            'metodo' => $metodo
        ];
    }


    public function despachar() { // motyor del router: analizar url y cargar lo necesario
        $url = $_SERVER['REQUEST_URI'] ?? '/'; // capturamos url
        $url = strtok($url, '?'); // limpiamos url
        $metodoHttp = $_SERVER['REQUEST_METHOD']; // comprobamos petición (GET o POST)

        if (isset($this->rutas[$metodoHttp][$url])) { // verificamos que la ruta existe
            $ruta = $this->rutas[$metodoHttp][$url];
            $nombreControlador = $ruta['controlador'];
            $nombreMetodo = $ruta['metodo'];

            // TEST TEXT xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
            echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; font-family: sans-serif;'>";
            echo "<h2>🚦 El Router está funcionando</h2>";
            echo "<p>Has pedido la URL: <b>$url</b></p>";
            echo "<p>El sistema intentará cargar el controlador: <b>$nombreControlador</b></p>";
            echo "<p>Y ejecutará la función: <b>$nombreMetodo</b></p>";
            echo "</div>";
            
        } else {
            http_response_code(404); // si la url no existe, mandamos error de php
            echo "<h2 style='color: red; font-family: sans-serif;'>Error 404 - Página no encontrada</h2>";
            echo "<p>La ruta <b>$url</b> no existe en ReparaYa.</p>";
        }
    }
}