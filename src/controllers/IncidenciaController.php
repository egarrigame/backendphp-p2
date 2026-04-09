<?php
// src/controllers/IncidenciaController.php

class IncidenciaController {
    
    public function guardar() { // método para guardar el formulario
        if (!isset($_SESSION['usuario_id'])) { // check login
            header('Location: /login');
            exit();
        }

        if ($_SESSION['usuario_rol'] === 'admin') {
            $cliente_id = $_POST['cliente_id'] ?? ''; // si es admin, el id vene del form
        } else {
            $cliente_id = $_SESSION['usuario_id']; // si es cliente, el id viene de la sesión
        }
 
        // datos del formulario
        $especialidad_id = $_POST['especialidad_id'] ?? '';
        $tipo_urgencia = $_POST['tipo_urgencia'] ?? 'Estándar';
        $fecha_servicio = $_POST['fecha_servicio'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($cliente_id) || empty($especialidad_id) || empty($fecha_servicio) || empty($direccion) || empty($descripcion)) { // check campos obligatorios
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

    public function actualizar() { // método para actualizar datos de incidencia desde formulario para admins
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        $id = $_POST['incidencia_id'] ?? '';
        $tecnico_id = $_POST['tecnico_id'] ?? '';
        $estado_id = $_POST['estado_id'] ?? '';

        if (empty($id) || empty($estado_id)) {
            header('Location: /panel?error=Faltan datos para actualizar.');
            exit();
        }

        require_once '../src/config/database.php';
        require_once '../src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        $exito = $incidenciaModel->actualizar($id, $tecnico_id, $estado_id);

        if ($exito) {
            header('Location: /panel?exito=Aviso actualizado correctamente.');
        } else {
            header('Location: /panel?error=Hubo un problema al actualizar el aviso.');
        }
        exit();
    }
}