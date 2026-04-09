<?php
// src/controllers/DashboardController.php

class DashboardController {
    
    public function index() {
        if (!isset($_SESSION['usuario_id'])) { // check de login
            header('Location: /login');
            exit();
        }

        $rol = $_SESSION['usuario_rol'];
        $datos = [
            'titulo' => 'Mi Panel',
            'nombre' => $_SESSION['usuario_nombre']
        ];

        if ($rol === 'admin') { // check de roles
            $this->render('panel_admin', $datos);
        } elseif ($rol === 'tecnico') {
            $this->render('panel_tecnico', $datos);
        } else {
            require_once '../src/config/database.php';
            require_once '../src/models/Incidencia.php';
            
            if (!function_exists('conectarDB')) {
                echo "Error: Falta la conexión a la base de datos."; return;
            }
            
            $db = conectarDB();
            $incidenciaModel = new Incidencia($db);
            $cliente_id = $_SESSION['usuario_id'];
            $datos['especialidades'] = $incidenciaModel->obtenerEspecialidades(); // obtenemos los datos dinámicos del modelo de incidencia
            $datos['mis_incidencias'] = $incidenciaModel->obtenerPorCliente($cliente_id);
            $datos['mensajeExito'] = $_GET['exito'] ?? null;
            $datos['error'] = $_GET['error'] ?? null;

            $this->render('panel_cliente', $datos);
        }
    }

    private function render($vista, $datos = []) { // método para cargar las vistas
        extract($datos);
        $rutaVista = '../src/views/' . $vista . '.php';

        if (file_exists($rutaVista)) {
            require_once $rutaVista;
        } else {
            // TEXT TESTTTTT xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
            echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
            echo "<h2>🚧 Panel en Construcción 🚧</h2>";
            echo "<p>El sistema detecta tu rol, pero la vista <b>$vista.php</b> todavía no existe.</p>";
            echo "<a href='/'>Volver al Inicio</a>";
            echo "</div>";
        }
    }
}