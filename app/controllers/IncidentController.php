<?php
/**
 * CONTROLADOR DE INCIDENCIAS (AVISOS)
 * 
 * Gestiona todas las acciones del cliente relacionadas con solicitudes:
 * - Mostrar formulario de nueva solicitud
 * - Crear nueva incidencia (con validación de 48h)
 * - Listar "Mis Avisos"
 * - Cancelar incidencia (con regla de 48h)
 * 
 * @author Equipo backendphp-p2
 */

class IncidentController extends Controller {
    
    // Instancia del modelo de incidencias
    private $incidentModel;
    
    /**
     * Constructor - Inicializa el modelo
     */
    public function __construct() {
        $this->incidentModel = new Incident();
    }
    
    /**
     * MOSTRAR FORMULARIO DE NUEVA SOLICITUD
     */
    public function newIncident() {
        // Verificar sesión
        $this->requireLogin();
     
        
        // Solo clientes pueden crear solicitudes
        if ($_SESSION['user_role'] !== 'particular') {
            $this->redirect('/dashboard');
        }
        
        // Obtener especialidades para el selector
        $especialidades = $this->incidentModel->getEspecialidades();
        
        // Mostrar vista
        $this->view('client/new_incident', [
            'especialidades' => $especialidades,
            'pageTitle' => 'Nueva Solicitud'
        ]);
    }
    
    /**
     * PROCESAR CREACIÓN DE NUEVA SOLICITUD
     */
    public function createIncident() {
        // Verificar sesión
        $this->requireLogin();
        
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/new-incident');
        }
        
        $errors = [];
        
        // Obtener datos del formulario
        $especialidad_id = $_POST['especialidad_id'] ?? '';
        $tipo_urgencia = $_POST['tipo_urgencia'] ?? '';
        $fecha_servicio = $_POST['fecha_servicio'] ?? '';
        $descripcion = trim($_POST['descripcion'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefono_contacto = trim($_POST['telefono_contacto'] ?? '');
        
        // VALIDACIONES
        if (empty($especialidad_id)) {
            $errors[] = 'Debes seleccionar un tipo de servicio';
        }
        if (empty($tipo_urgencia)) {
            $errors[] = 'Debes seleccionar el tipo de urgencia';
        }
        if (empty($fecha_servicio)) {
            $errors[] = 'Debes seleccionar una fecha para el servicio';
        }
        if (empty($descripcion)) {
            $errors[] = 'La descripción de la avería es obligatoria';
        }
        if (empty($direccion)) {
            $errors[] = 'La dirección es obligatoria';
        }
        if (empty($telefono_contacto)) {
            $errors[] = 'El teléfono de contacto es obligatorio';
        }
        
        // Validar regla de 48 horas
        if (empty($errors)) {
            $canCreate = $this->incidentModel->canCreateIncident($fecha_servicio, $tipo_urgencia);
            if (!$canCreate) {
                $errors[] = 'Los servicios estándar requieren al menos 48 horas de antelación';
            }
        }
        
        // Si hay errores, volver al formulario
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            $this->redirect('/new-incident');
        }
        
        // Preparar datos
        $data = [
            'cliente_id' => $_SESSION['user_id'],
            'especialidad_id' => $especialidad_id,
            'descripcion' => $descripcion,
            'direccion' => $direccion,
            'fecha_servicio' => $fecha_servicio,
            'tipo_urgencia' => $tipo_urgencia,
            'telefono_contacto' => $telefono_contacto,
            'estado' => 'Pendiente'
        ];
        
        // Crear incidencia
        if ($this->incidentModel->createIncident($data)) {
            $_SESSION['success'] = 'Solicitud creada correctamente.';
            $this->redirect('/my-incidents');
        } else {
            $_SESSION['errors'] = ['Error al crear la solicitud'];
            $this->redirect('/new-incident');
        }
    }
    
    /**
     * MOSTRAR LISTADO "MIS AVISOS"
     */
    public function myIncidents() {
        // Verificar sesión
        $this->requireLogin();
        
        // Solo clientes
        if ($_SESSION['user_role'] !== 'particular') {
            $this->redirect('/dashboard');
        }
        
        // Obtener incidencias del cliente
        $incidents = $this->incidentModel->getByClient($_SESSION['user_id']);
        
        // Mostrar vista
        $this->view('client/my_incidents', [
            'incidents' => $incidents,
            'pageTitle' => 'Mis Avisos'
        ]);
    }
    
    /**
     * PROCESAR CANCELACIÓN DE INCIDENCIA
     */
    public function cancelIncident() {
        // Verificar sesión
        $this->requireLogin();
        
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/my-incidents');
        }
        
        $incidentId = $_POST['incident_id'] ?? 0;
        
        // Verificar si se puede cancelar
        if ($this->incidentModel->canCancel($incidentId, $_SESSION['user_id'])) {
            if ($this->incidentModel->cancelIncident($incidentId)) {
                $_SESSION['success'] = 'Aviso cancelado correctamente';
            } else {
                $_SESSION['errors'] = ['Error al cancelar el aviso'];
            }
        } else {
            $_SESSION['errors'] = [
                'No se puede cancelar este aviso. ' .
                'Los servicios estándar requieren al menos 48 horas de antelación.'
            ];
        }
        
        $this->redirect('/my-incidents');
    }
}