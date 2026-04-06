<?php
/**
 * MODELO DE INCIDENCIAS (AVISOS)
 * 
 * Gestiona todas las operaciones relacionadas con las solicitudes de servicio:
 * - Crear nueva incidencia (con validación de 48h)
 * - Listar incidencias de un cliente
 * - Cancelar incidencias (con regla de 48h)
 * - Generar código único de seguimiento
 * 
 * @author Equipo backendphp-p2
 */

class Incident extends Model {
    
    // Nombre de la tabla en la base de datos
    protected $table = 'incidencias';
    
    /**
     * GENERAR CÓDIGO ÚNICO DE INCIDENCIA (LOCALIZADOR)
     * 
     * Formato: INC-YYYYMMDD-XXX
     * Ejemplo: INC-20250405-001 (primera incidencia del día)
     * 
     * @return string Código único para identificar la incidencia
     */
    public function generateLocalizador() {
        // Fecha actual en formato AÑOMESDÍA (ej: 20250405)
        $date = date('Ymd');
        
        // Contar cuántas incidencias se han creado HOY
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM incidencias 
            WHERE localizador LIKE ?
        ");
        $stmt->execute(["INC-{$date}-%"]);
        $result = $stmt->fetch();
        
        // Sumamos 1 al total de hoy y formateamos con 3 dígitos
        $next = str_pad(($result['total'] + 1), 3, '0', STR_PAD_LEFT);
        
        // Retornamos el código completo
        return "INC-{$date}-{$next}";
    }
    
    /**
     * VERIFICAR SI SE PUEDE CREAR UNA INCIDENCIA EN UNA FECHA ESPECÍFICA
     * 
     * REGLA DE NEGOCIO:
     * - Servicios ESTÁNDAR: requieren al menos 48 horas de antelación
     * - Servicios URGENTES: se pueden crear sin restricción
     * 
     * @param string $scheduledDate Fecha solicitada
     * @param string $type Tipo de urgencia ('Estándar' o 'Urgente')
     * @return bool True si se puede crear
     */
    public function canCreateIncident($scheduledDate, $type) {
        // Solo aplica restricción para servicios estándar
        if ($type === 'Estándar') {
            $today = new DateTime();
            $scheduled = new DateTime($scheduledDate);
            
            // Diferencia en días
            $diff = $today->diff($scheduled)->days;
            
            // Necesita al menos 2 días (48 horas) de antelación
            return $diff >= 2;
        }
        
        // Servicios urgentes sin restricción
        return true;
    }
    
    /**
     * VERIFICAR SI SE PUEDE CANCELAR UNA INCIDENCIA
     * 
     * REGLA DE NEGOCIO:
     * - Servicios URGENTES: se pueden cancelar en cualquier momento
     * - Servicios ESTÁNDAR: solo con al menos 48 horas de antelación
     * 
     * @param int $incidentId ID de la incidencia
     * @param int $clientId ID del cliente
     * @return bool True si se puede cancelar
     */
    public function canCancel($incidentId, $clientId) {
        // Obtener la incidencia
        $stmt = $this->db->prepare("
            SELECT tipo_urgencia, fecha_servicio, estado 
            FROM incidencias 
            WHERE id = ? AND cliente_id = ?
        ");
        $stmt->execute([$incidentId, $clientId]);
        $incident = $stmt->fetch();
        
        // Si no existe o ya está cancelada
        if (!$incident || $incident['estado'] === 'Cancelada') {
            return false;
        }
        
        // Si ya está asignada o finalizada
        if (in_array($incident['estado'], ['Asignada', 'Finalizada'])) {
            return false;
        }
        
        // Verificar regla de 48h para estándar
        if ($incident['tipo_urgencia'] === 'Estándar') {
            $today = new DateTime();
            $serviceDate = new DateTime($incident['fecha_servicio']);
            $diff = $today->diff($serviceDate)->days;
            
            if ($diff < 2) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * CREAR NUEVA INCIDENCIA
     * 
     * @param array $data Datos de la incidencia
     * @return bool True si se creó correctamente
     */
    public function createIncident($data) {
        // Generar código único
        $data['localizador'] = $this->generateLocalizador();
        
        // Estado por defecto
        $data['estado'] = $data['estado'] ?? 'Pendiente';
        
        $stmt = $this->db->prepare("
            INSERT INTO incidencias 
            (localizador, cliente_id, especialidad_id, descripcion, direccion, 
             fecha_servicio, tipo_urgencia, estado, telefono_contacto) 
            VALUES 
            (:localizador, :cliente_id, :especialidad_id, :descripcion, :direccion,
             :fecha_servicio, :tipo_urgencia, :estado, :telefono_contacto)
        ");
        
        return $stmt->execute([
            'localizador' => $data['localizador'],
            'cliente_id' => $data['cliente_id'],
            'especialidad_id' => $data['especialidad_id'],
            'descripcion' => $data['descripcion'],
            'direccion' => $data['direccion'],
            'fecha_servicio' => $data['fecha_servicio'],
            'tipo_urgencia' => $data['tipo_urgencia'],
            'estado' => $data['estado'],
            'telefono_contacto' => $data['telefono_contacto'] ?? null
        ]);
    }
    
    /**
     * CANCELAR UNA INCIDENCIA
     * 
     * @param int $incidentId ID de la incidencia
     * @return bool True si se canceló
     */
    public function cancelIncident($incidentId) {
        $stmt = $this->db->prepare("
            UPDATE incidencias 
            SET estado = 'Cancelada' 
            WHERE id = ?
        ");
        return $stmt->execute([$incidentId]);
    }
    
    /**
     * OBTENER INCIDENCIAS DE UN CLIENTE
     * 
     * @param int $clientId ID del cliente
     * @return array Lista de incidencias
     */
    public function getByClient($clientId) {
        $stmt = $this->db->prepare("
            SELECT i.*, e.nombre_especialidad 
            FROM incidencias i
            LEFT JOIN especialidades e ON i.especialidad_id = e.id
            WHERE i.cliente_id = ?
            ORDER BY i.fecha_servicio DESC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }
    
    /**
     * OBTENER TODAS LAS ESPECIALIDADES
     * 
     * @return array Lista de especialidades
     */
    public function getEspecialidades() {
        $stmt = $this->db->query("SELECT * FROM especialidades ORDER BY nombre_especialidad");
        return $stmt->fetchAll();
    }
}