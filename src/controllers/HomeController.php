<?php
// src/controllers/HomeController.php

class HomeController {
    
    
    public function index() { // método para llamar al router cuando alguien entre en index
        
        $datos = [
            'titulo' => 'Bienvenido a ReparaYa',
            'mensaje' => 'Aplicación de gestión de averías domésticas.'
        ];

        $this->render('home', $datos); //método para buscar y cargar el html
    }

    private function render($vista, $datos = []) { // helper para cargar el html en lugar de hacer require en cada método, le pasamos el nombre de la vista y array de datos y busca el archivo en la carpeta de vistas e inyecta las variables con la función extract

        extract($datos); // sacamos variables de los array para convertirlas en variables normales y usarlas en el html, ej: $datos['titulo'] pasa a título

        $rutaVista = '../src/views/' . $vista . '.php'; // ruta física para cargar la vista

        if (file_exists($rutaVista)) { // comprobamos que la vista existe
            require_once $rutaVista;
        } else {
            echo "Error Crítico: No se encuentra la vista $rutaVista";
        }
    }
}