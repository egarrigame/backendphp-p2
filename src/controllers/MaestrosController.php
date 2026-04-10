<?php
// src/controllers/MaestrosController.php

class MaestrosController {

    public function index() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: /~uocx1/login');
            exit();
        }

        require_once 'src/config/database.php';
        require_once 'src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        $datos = [
            'titulo' => 'Maestros - ReparaYa',
            'tecnicos' => $incidenciaModel->obtenerTecnicos(),
            'especialidades' => $incidenciaModel->obtenerEspecialidades(),
            'mensajeExito' => $_GET['exito'] ?? null,
            'error' => $_GET['error'] ?? null
        ];

        $this->render('panel_maestros', $datos);
    }

    public function guardarTecnico() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            exit();
        }

        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $password = $_POST['password'] ?? '';
        $especialidad_id = $_POST['especialidad_id'] ?? '';

        if(empty($nombre) || empty($email) || empty($telefono) || empty($password) || empty($especialidad_id)) {
            header('Location: /~uocx1/maestros?error=Faltan datos obligatorios');
            exit();
        }

        require_once 'src/config/database.php';
        require_once 'src/models/Usuario.php';
        $db = conectarDB();
        $usuarioModel = new Usuario($db);

        $exito = $usuarioModel->crearTecnico($nombre, $email, $telefono, $password, $especialidad_id);

        if ($exito) {
            header('Location: /~uocx1/maestros?exito=Técnico dado de alta correctamente');
        } else {
            header('Location: /~uocx1/maestros?error=Error al guardar.');
        }
        exit();
    }

    public function cambiarEstado() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') exit();

        $tecnico_id = $_POST['tecnico_id'] ?? '';
        $estado_actual = $_POST['estado_actual'] ?? '0';
        $nuevo_estado = ($estado_actual == '1') ? 0 : 1;

        require_once 'src/config/database.php';
        require_once 'src/models/Usuario.php';
        $db = conectarDB();
        $usuarioModel = new Usuario($db);

        if ($usuarioModel->actualizarEstadoTecnico($tecnico_id, $nuevo_estado)) {
            header('Location: /~uocx1/maestros?exito=Estado del técnico actualizado');
        } else {
            header('Location: /~uocx1/maestros?error=Error al cambiar el estado');
        }
        exit();
    }

    private function render($vista, $datos = []) {
        extract($datos);
        require_once 'src/views/' . $vista . '.php';
    }
}