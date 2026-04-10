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

        $url = str_replace('/~uocx1', '', $url); // sacamos la carpeta de la uoc
        
        if ($url === '') { // forzamos home al quedarse vacío
            $url = '/';
        }

        $url = strtok($url, '?'); // limpiamos url
        $metodoHttp = $_SERVER['REQUEST_METHOD']; // comprobamos petición (GET o POST)

        if (isset($this->rutas[$metodoHttp][$url])) { // verificamos que la ruta existe
            $ruta = $this->rutas[$metodoHttp][$url];
            $nombreControlador = $ruta['controlador'];
            $nombreMetodo = $ruta['metodo'];

            $rutaControlador = 'src/controllers/' . $nombreControlador . '.php'; // ruta física hasta el archivo de controlador
            if (file_exists($rutaControlador)) { // comprobamos que el archivo exista
                
                require_once $rutaControlador; // importamos el archivo
                $controladorObj = new $nombreControlador(); // instanciamos el controlador

                if (method_exists($controladorObj, $nombreMetodo)) { // comprobamos que la función exista
                    $controladorObj->$nombreMetodo(); // ejecutamos el método del controlador
                } else {
                    echo "<h3>Error de Código 500</h3>";
                    echo "<p>El Controlador existe, pero no tiene una función llamada <b>$nombreMetodo</b>.</p>";
                }
            } else {
                echo "<h3>Error de Código 500</h3>";
                echo "<p>El Router intentó cargar <b>$rutaControlador</b> pero el archivo no existe.</p>";
            }
            
        } else {
            http_response_code(404); // si la url no existe, mandamos error de php
            echo "<h2 style='color: red; font-family: sans-serif;'>Error 404 - Página no encontrada</h2>";
            echo "<p>La ruta <b>$url</b> no existe en ReparaYa.</p>";
        }
    }
}