<?php
// src/controllers/DashboardController.php

class DashboardController {
    
    public function index() {
        if (!isset($_SESSION['usuario_id'])) { // check de login
            header('Location: /login');
            exit();
        }

        $rol = $_SESSION['usuario_rol'];
        $cliente_id = $_SESSION['usuario_id'];
        $datos = [
            'titulo' => 'Mi Panel',
            'nombre' => $_SESSION['usuario_nombre']
        ];

        require_once '../src/config/database.php';
        require_once '../src/models/Incidencia.php';

        $datos['mensajeExito'] = $_GET['exito'] ?? null;
        $datos['error'] = $_GET['error'] ?? null;

        if (!function_exists('conectarDB')) {
            echo "Error: Falta la conexión a la base de datos."; return;
        }
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        if ($rol === 'admin') { // check de roles
            require_once '../src/models/Usuario.php';
            $usuarioModel = new Usuario($db);
            $datos['todas_incidencias'] = $incidenciaModel->obtenerTodas();
            $datos['especialidades'] = $incidenciaModel->obtenerEspecialidades();
            $datos['clientes'] = $usuarioModel->obtenerClientes();
            $datos['tecnicos'] = $incidenciaModel->obtenerTecnicos();
            $datos['estados'] = $incidenciaModel->obtenerEstados();
            $this->render('panel_admin', $datos);

        } elseif ($rol === 'tecnico') {
            $datos['mis_trabajos'] = $incidenciaModel->obtenerPorTecnico($_SESSION['usuario_id']);
            $this->render('panel_tecnico', $datos);

        } else {
            $datos['especialidades'] = $incidenciaModel->obtenerEspecialidades();
            $datos['mis_incidencias'] = $incidenciaModel->obtenerPorCliente($cliente_id);
            $this->render('panel_cliente', $datos);
        }
    }

    public function calendario() { // método para mostrar el calendario a admins
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        $datos = [
            'titulo' => 'Calendario General - ReparaYa',
            'nombre' => $_SESSION['usuario_nombre']
        ];
        
        $this->render('calendario_admin', $datos);
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