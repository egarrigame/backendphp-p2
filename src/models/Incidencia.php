<?php
// src/models/Incidencia.php

class Incidencia {
    private $db;

    public function __construct($conexionDB) {
        $this->db = $conexionDB;
    }

    public function obtenerEspecialidades() { // traemos las especialidades de la bbdd
        $sql = "SELECT id, nombre_especialidad FROM especialidades";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCliente($cliente_id) { // método para obtener incidencias por cliente
        $sql = "SELECT i.*,   
                       e.nombre_estado, 
                       e.color_calendario, 
                       esp.nombre_especialidad 
                FROM incidencias i
                INNER JOIN estados e ON i.estado_id = e.id
                INNER JOIN especialidades esp ON i.especialidad_id = esp.id
                WHERE i.cliente_id = :cliente_id
                ORDER BY i.fecha_servicio DESC"; // join con estado y especialidad
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($cliente_id, $especialidad_id, $tipo_urgencia, $fecha_servicio, $direccion, $descripcion) { // método para crear incidencia
        try {
            $localizador = 'REP-' . strtoupper(substr(uniqid(), -8)); // localizador automático formateado

            $sql = "INSERT INTO incidencias (localizador, cliente_id, especialidad_id, estado_id, descripcion, direccion, fecha_servicio, tipo_urgencia) 
                    VALUES (:localizador, :cliente_id, :especialidad_id, 1, :descripcion, :direccion, :fecha_servicio, :tipo_urgencia)";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':localizador' => $localizador,
                ':cliente_id' => $cliente_id,
                ':especialidad_id' => $especialidad_id,
                ':descripcion' => $descripcion,
                ':direccion' => $direccion,
                ':fecha_servicio' => $fecha_servicio,
                ':tipo_urgencia' => $tipo_urgencia
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerTodas() { // método para tener todas las incidencias par admins
        $sql = "SELECT i.*, 
                       u.nombre AS nombre_cliente,
                       e.nombre_estado, 
                       e.color_calendario, 
                       esp.nombre_especialidad,
                       t.nombre_completo AS nombre_tecnico
                FROM incidencias i
                INNER JOIN usuarios u ON i.cliente_id = u.id
                INNER JOIN estados e ON i.estado_id = e.id
                INNER JOIN especialidades esp ON i.especialidad_id = esp.id
                LEFT JOIN tecnicos t ON i.tecnico_id = t.id
                ORDER BY i.fecha_servicio DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTecnicos() { // método para obtener todos los te´cnicos
        $sql = "SELECT id, nombre_completo, especialidad_id FROM tecnicos ORDER BY nombre_completo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstados() { // método para obtener todos los estados
        $sql = "SELECT id, nombre_estado FROM estados ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $tecnico_id, $estado_id) { // método para poder actualizar incidencias
        try {
            $tecnico_id = !empty($tecnico_id) ? $tecnico_id : null;

            $sql = "UPDATE incidencias 
                    SET tecnico_id = :tecnico_id, estado_id = :estado_id 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':tecnico_id' => $tecnico_id,
                ':estado_id' => $estado_id,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}