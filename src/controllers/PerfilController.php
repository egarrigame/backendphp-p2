<?php
// src/controllers/PerfilController.php

class PerfilController {

    public function index() { // méotod para mostrar el form de cambio de datos del perfil
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /~uocx1/login');
            exit();
        }

        require_once 'src/config/database.php';
        require_once 'src/models/Usuario.php';
        $db = conectarDB();
        $usuarioModel = new Usuario($db);

        $datos = [
            'titulo' => 'Mi Perfil - ReparaYa',
            'usuario' => $usuarioModel->obtenerPorId($_SESSION['usuario_id']),
            'mensajeExito' => $_GET['exito'] ?? null,
            'error' => $_GET['error'] ?? null
        ];

        $this->render('perfil', $datos);
    }

    public function guardar() { // método para procesar los cambios (petición POST)
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /~uocx1/login');
            exit();
        }

        $id = $_SESSION['usuario_id'];
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? ''; // Opcional

        if (empty($nombre) || empty($email)) {
            header('Location: /~uocx1/perfil?error=El nombre y el email son obligatorios.');
            exit();
        }

        require_once 'src/config/database.php';
        require_once 'src/models/Usuario.php';
        $db = conectarDB();
        $usuarioModel = new Usuario($db);

        $exito = $usuarioModel->actualizarPerfil($id, $nombre, $email, $password);

        if ($exito) {
            $_SESSION['usuario_nombre'] = $nombre; // actualizamos la sesión si cambia el nombre (está en display en el navbar)
            
            header('Location: /~uocx1/perfil?exito=Perfil actualizado correctamente.');
        } else {
            header('Location: /~uocx1/perfil?error=Error al actualizar. Quizás el email ya esté en uso.');
        }
        exit();
    }

    private function render($vista, $datos = []) {
        extract($datos);
        require_once 'src/views/' . $vista . '.php';
    }
}