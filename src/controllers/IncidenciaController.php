<?php
// src/controllers/IncidenciaController.php

class IncidenciaController {
    
    public function guardar() { // método para guardar el formulario
        if (!isset($_SESSION['usuario_id'])) { // check login
            header('Location: /~uocx1/login');
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
            header('Location: /~uocx1/panel?error=Faltan datos obligatorios');
            exit();
        }

        if ($tipo_urgencia === 'Estándar') { // validación 48h para estándar
            $fechaSolicitadaObj = new DateTime($fecha_servicio); // convertimos fechas a objetos para poder comparar
            $fechaMinimaPermitida = new DateTime(); // fecha actual + 48h
            $fechaMinimaPermitida->modify('+48 hours');

            if ($fechaSolicitadaObj < $fechaMinimaPermitida) { // rechazamos si es menor
                header('Location: /~uocx1/panel?error=Los servicios estándar requieren 48h de antelación');
                exit();
            }
        }

        require_once 'src/config/database.php'; // guardamos en la bbdd
        require_once 'src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        $exito = $incidenciaModel->crear($cliente_id, $especialidad_id, $tipo_urgencia, $fecha_servicio, $direccion, $descripcion);

        if ($exito) {
            header('Location: /~uocx1/panel?exito=Incidencia creada correctamente');
        } else {
            header('Location: /~uocx1/panel?error=Hubo un problema al guardar la incidencia.');
        }
        exit();
    }

    public function actualizar() { // método para actualizar datos de incidencia desde formulario para admins
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: /~uocx1/login');
            exit();
        }

        $id = $_POST['incidencia_id'] ?? '';
        $tecnico_id = $_POST['tecnico_id'] ?? '';
        $estado_id = $_POST['estado_id'] ?? '';
        $fecha_servicio = $_POST['fecha_servicio'] ?? '';
        $tipo_urgencia = $_POST['tipo_urgencia'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($id) || empty($estado_id) || empty($fecha_servicio)) {
            header('Location: /~uocx1/panel?error=Faltan datos para actualizar.');
            exit();
        }

        require_once 'src/config/database.php';
        require_once 'src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        $exito = $incidenciaModel->actualizar($id, $tecnico_id, $estado_id, $fecha_servicio, $tipo_urgencia, $direccion, $descripcion);

        if ($exito) {
            header('Location: /~uocx1/panel?exito=Aviso actualizado correctamente.');
        } else {
            header('Location: /~uocx1/panel?error=Hubo un problema al actualizar el aviso.');
        }
        exit();
    }

    public function apiTodas() { // API para traer datos de la bbdd en JSON y formatear calendario
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['error' => 'No autorizado']);
            exit();
        }

        // traemos los datos
        require_once 'src/config/database.php';
        require_once 'src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);
        
        $incidencias = $incidenciaModel->obtenerTodas();

        $eventos = []; // fromatear el calendar
        foreach ($incidencias as $inc) {
            $color = ($inc['tipo_urgencia'] === 'Urgente') ? '#dc3545' : '#0d6efd'; // Rojo urgente, Azul estándar

            $eventos[] = [
                'id' => $inc['id'],
                'title' => $inc['nombre_especialidad'] . ' - ' . $inc['localizador'],
                'start' => $inc['fecha_servicio'],
                'color' => $color,
                'extendedProps' => [
                    'cliente' => $inc['nombre_cliente'],
                    'direccion' => $inc['direccion'],
                    'descripcion' => $inc['descripcion'],
                    'estado' => $inc['nombre_estado'],
                    'tecnico' => $inc['nombre_tecnico'] ?? 'Sin asignar',
                    'urgencia' => $inc['tipo_urgencia']
                ]
            ];
        }

        header('Content-Type: application/json'); // devuelve JSON
        echo json_encode($eventos);
        exit();
    }

    public function cancelar() { // método para cancelar una incidencia
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'particular') {
            exit();
        }

        $incidencia_id = $_POST['incidencia_id'] ?? '';
        $cliente_id = $_SESSION['usuario_id'];

        if (empty($incidencia_id)) {
            header('Location: /~uocx1/panel?error=Aviso no válido.');
            exit();
        }

        require_once 'src/config/database.php';
        require_once 'src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        $incidencia = $incidenciaModel->obtenerPorId($incidencia_id); // buscamos la incidencia para ver la fecha

        if (!$incidencia || $incidencia['cliente_id'] != $cliente_id) { // comprobación que existe y es del user
            header('Location: /~uocx1/panel?error=Operación no permitida.');
            exit();
        }

        $fechaServicio = new DateTime($incidencia['fecha_servicio']); // calculamos el tiempo igual que al dar de alta
        $fechaLimiteParaCancelar = new DateTime();
        $fechaLimiteParaCancelar->modify('+48 hours');

        if ($fechaServicio < $fechaLimiteParaCancelar) { // bloquemaos si es menor a 48h
            header('Location: /~uocx1/panel?error=No se puede cancelar con menos de 48 horas de antelación');
            exit();
        }

        if ($incidenciaModel->cancelarPorCliente($incidencia_id, $cliente_id)) {
            header('Location: /~uocx1/panel?exito=Aviso cancelado correctamente.');
        } else {
            header('Location: /~uocx1/panel?error=Error al cancelar.');
        }
        exit();
    }

    public function eliminar() { // método para eliminar incidencia
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') exit();

        $id = $_POST['incidencia_id'] ?? '';

        require_once 'src/config/database.php';
        require_once 'src/models/Incidencia.php';
        $db = conectarDB();
        $incidenciaModel = new Incidencia($db);

        if ($incidenciaModel->eliminar($id)) {
            header('Location: /~uocx1/panel?exito=Aviso borrado de la base de datos.');
        } else {
            header('Location: /~uocx1/panel?error=Error al eliminar.');
        }
        exit();
    }
}