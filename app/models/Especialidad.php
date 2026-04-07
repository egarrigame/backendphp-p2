<?php
/**
 * MODELO DE ESPECIALIDADES (TIPOS DE SERVICIO)
 * 
 * Gestiona los tipos de servicios disponibles:
 * - Fontanería, Electricidad, Carpintería, Pintura, Jardinería
 * 
 * @author Equipo backendphp-p2
 */

class Especialidad extends Model {
    
    // Nombre de la tabla en la base de datos
    protected $table = 'especialidades';
    
    /**
     * OBTENER TODAS LAS ESPECIALIDADES
     * 
     * @return array Lista de especialidades ordenadas por nombre
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM especialidades ORDER BY nombre_especialidad");
        return $stmt->fetchAll();
    }
    
    /**
     * CREAR NUEVA ESPECIALIDAD
     * 
     * @param string $nombre Nombre de la especialidad
     * @return bool True si se creó correctamente
     */
    public function createEspecialidad($nombre) {
        $stmt = $this->db->prepare("INSERT INTO especialidades (nombre_especialidad) VALUES (?)");
        return $stmt->execute([$nombre]);
    }
    
    /**
     * ELIMINAR ESPECIALIDAD
     * 
     * @param int $id ID de la especialidad
     * @return bool True si se eliminó
     */
    public function deleteEspecialidad($id) {
        $stmt = $this->db->prepare("DELETE FROM especialidades WHERE id = ?");
        return $stmt->execute([$id]);
    }
}