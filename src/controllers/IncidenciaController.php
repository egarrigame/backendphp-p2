<?php
// src/controllers/IncidenciaController.php

class IncidenciaController {
    
    public function guardar() { // método para guardar el formulario
        if (!isset($_SESSION['usuario_id'])) { // check login
            header('Location: /login');
            exit();
        }

        $cliente_id = $_SESSION['usuario_id']; // recogemos datos del formulario
        $especialidad_id = $_POST['especialidad_id'] ?? '';
        $tipo_urgencia = $_POST['tipo_urgencia'] ?? 'Estándar';
        $fecha_servicio = $_POST['fecha_servicio'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($especialidad_id) || empty($fecha_servicio) || empty($direccion) || empty($descripcion)) { // check campos obligatorios
            header('Location: /panel?error=Faltan datos obligatorios');
            exit();
        }

        require_once '../src/config/database.php'; // guardamos en la bbdd
        require_once '../src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        $exito = $incidenciaModel->crear($cliente_id, $especialidad_id, $tipo_urgencia, $fecha_servicio, $direccion, $descripcion);

        if ($exito) {
            header('Location: /panel?exito=Incidencia creada correctamente');
        } else {
            header('Location: /panel?error=Hubo un problema al guardar la incidencia.');
        }
        exit();
    }
}