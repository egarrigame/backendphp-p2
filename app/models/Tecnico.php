<?php
/**
 * MODELO DE TÉCNICOS
 * 
 * Gestiona los técnicos del sistema:
 * - Dar de alta/baja técnicos
 * - Asignar especialidades
 * - Cambiar disponibilidad
 * 
 * @author Equipo backendphp-p2
 */

class Tecnico extends Model {
    
    // Nombre de la tabla en la base de datos
    protected $table = 'tecnicos';
    
    /**
     * OBTENER TODOS LOS TÉCNICOS CON SUS DATOS
     * 
     * @return array Lista completa de técnicos
     */
    public function getAllWithDetails() {
        $stmt = $this->db->prepare("
            SELECT t.*, u.nombre, u.email, u.telefono, e.nombre_especialidad
            FROM tecnicos t
            LEFT JOIN usuarios u ON t.usuario_id = u.id
            LEFT JOIN especialidades e ON t.especialidad_id = e.id
            ORDER BY t.nombre_completo
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * CREAR NUEVO TÉCNICO
     * 
     * @param array $data Datos del técnico
     * @return bool True si se creó correctamente
     */
    public function createTecnico($data) {
        $stmt = $this->db->prepare("
            INSERT INTO tecnicos (usuario_id, nombre_completo, especialidad_id, disponible) 
            VALUES (:usuario_id, :nombre_completo, :especialidad_id, :disponible)
        ");
        
        return $stmt->execute([
            'usuario_id' => $data['usuario_id'] ?? null,
            'nombre_completo' => $data['nombre_completo'],
            'especialidad_id' => $data['especialidad_id'],
            'disponible' => $data['disponible'] ?? 1
        ]);
    }
    
    /**
     * ACTUALIZAR TÉCNICO
     * 
     * @param int $id ID del técnico
     * @param array $data Datos a actualizar
     * @return bool True si se actualizó
     */
    public function updateTecnico($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE tecnicos 
            SET nombre_completo = :nombre_completo, 
                especialidad_id = :especialidad_id, 
                disponible = :disponible 
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'nombre_completo' => $data['nombre_completo'],
            'especialidad_id' => $data['especialidad_id'],
            'disponible' => $data['disponible']
        ]);
    }
    
    /**
     * ELIMINAR TÉCNICO
     * 
     * @param int $id ID del técnico
     * @return bool True si se eliminó
     */
    public function deleteTecnico($id) {
        $stmt = $this->db->prepare("DELETE FROM tecnicos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * CAMBIAR DISPONIBILIDAD DE UN TÉCNICO
     * 
     * @param int $id ID del técnico
     * @param bool $disponible True = disponible, False = no disponible
     * @return bool True si se actualizó
     */
    public function setDisponibilidad($id, $disponible) {
        $stmt = $this->db->prepare("UPDATE tecnicos SET disponible = ? WHERE id = ?");
        return $stmt->execute([$disponible ? 1 : 0, $id]);
    }
}