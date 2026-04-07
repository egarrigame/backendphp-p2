
 <?php
/**
 * CONTROLADOR DE ADMINISTRACIÓN
 * 
 * Gestiona todas las funciones del panel de administración:
 * - CRUD de técnicos
 * - CRUD de incidencias (modificar/cancelar)
 * - Asignar técnicos a incidencias
 * - Calendario (diario/semanal/mensual)
 */

class AdminController extends Controller {
    
    private $tecnicoModel;
    private $incidentModel;
    private $especialidadModel;
    
    public function __construct() {
        $this->tecnicoModel = new Tecnico();
        $this->incidentModel = new Incident();
        $this->especialidadModel = new Especialidad();
    }
    
    /**
     * DASHBOARD DEL ADMINISTRADOR
     */
    public function dashboard() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $this->view('admin/dashboard', [
            'pageTitle' => 'Panel de Administración'
        ]);
    }
    
    /**
     * ============ CRUD DE TÉCNICOS ============
     */
    
    public function tecnicos() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $tecnicos = $this->tecnicoModel->getAllWithDetails();
        $especialidades = $this->especialidadModel->getAll();
        
        $this->view('admin/tecnicos', [
            'tecnicos' => $tecnicos,
            'especialidades' => $especialidades,
            'pageTitle' => 'Gestión de Técnicos'
        ]);
    }
    
    public function createTecnico() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/tecnicos');
        }
        
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'especialidad_id' => $_POST['especialidad_id'] ?? '',
            'disponible' => isset($_POST['disponible']) ? 1 : 0,
            'usuario_id' => null
        ];
        
        if ($this->tecnicoModel->createTecnico($data)) {
            $_SESSION['success'] = 'Técnico creado correctamente';
        } else {
            $_SESSION['errors'] = ['Error al crear el técnico'];
        }
        
        $this->redirect('/admin/tecnicos');
    }
    
    public function updateTecnico() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/tecnicos');
        }
        
        $id = $_POST['id'] ?? 0;
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'especialidad_id' => $_POST['especialidad_id'] ?? '',
            'disponible' => isset($_POST['disponible']) ? 1 : 0
        ];
        
        if ($this->tecnicoModel->updateTecnico($id, $data)) {
            $_SESSION['success'] = 'Técnico actualizado correctamente';
        } else {
            $_SESSION['errors'] = ['Error al actualizar el técnico'];
        }
        
        $this->redirect('/admin/tecnicos');
    }
    
    public function deleteTecnico() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/tecnicos');
        }
        
        $id = $_POST['id'] ?? 0;
        
        if ($this->tecnicoModel->deleteTecnico($id)) {
            $_SESSION['success'] = 'Técnico eliminado correctamente';
        } else {
            $_SESSION['errors'] = ['Error al eliminar el técnico'];
        }
        
        $this->redirect('/admin/tecnicos');
    }
    
    /**
     * ============ CALENDARIO ============
     */
           public function calendar() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $view = $_GET['view'] ?? 'monthly';
        $incidencias = $this->incidentModel->getForCalendar();
        $pageTitle = 'Calendario de Servicios';
        
        // Cargar la vista directamente
        $viewFile = __DIR__ . '/../views/admin/calendar.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "Archivo no encontrado: " . $viewFile;
        }
    }
     
    /**
     * ============ CRUD DE INCIDENCIAS (ADMIN) ============
     */
    
    public function incidencias() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $incidencias = $this->incidentModel->getAllWithDetails();
        $especialidades = $this->especialidadModel->getAll();
        $tecnicos = $this->tecnicoModel->getAllWithDetails();
        
        $this->view('admin/incidencias', [
            'incidencias' => $incidencias,
            'especialidades' => $especialidades,
            'tecnicos' => $tecnicos,
            'pageTitle' => 'Gestión de Incidencias'
        ]);
    }
    
    public function updateIncident() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/incidencias');
        }
        
        $id = $_POST['id'] ?? 0;
        
        $stmt = $this->incidentModel->db->prepare("
            UPDATE incidencias 
            SET especialidad_id = :especialidad_id,
                tipo_urgencia = :tipo_urgencia,
                fecha_servicio = :fecha_servicio,
                descripcion = :descripcion,
                direccion = :direccion,
                estado = :estado
            WHERE id = :id
        ");
        
        $result = $stmt->execute([
            'id' => $id,
            'especialidad_id' => $_POST['especialidad_id'],
            'tipo_urgencia' => $_POST['tipo_urgencia'],
            'fecha_servicio' => $_POST['fecha_servicio'],
            'descripcion' => $_POST['descripcion'],
            'direccion' => $_POST['direccion'],
            'estado' => $_POST['estado']
        ]);
        
        if ($result) {
            $_SESSION['success'] = 'Incidencia actualizada correctamente';
        } else {
            $_SESSION['errors'] = ['Error al actualizar la incidencia'];
        }
        
        $this->redirect('/admin/incidencias');
    }
    
    public function cancelIncident() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/incidencias');
        }
        
        $id = $_POST['id'] ?? 0;
        
        $stmt = $this->incidentModel->db->prepare("
            UPDATE incidencias SET estado = 'Cancelada' WHERE id = ?
        ");
        
        if ($stmt->execute([$id])) {
            $_SESSION['success'] = 'Incidencia cancelada correctamente';
        } else {
            $_SESSION['errors'] = ['Error al cancelar la incidencia'];
        }
        
        $this->redirect('/admin/incidencias');
    }
    
    public function assignTechnician() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/incidencias');
        }
        
        $incidentId = $_POST['incident_id'] ?? 0;
        $tecnicoId = $_POST['tecnico_id'] ?? null;
        
        if ($tecnicoId) {
            $stmt = $this->incidentModel->db->prepare("
                UPDATE incidencias 
                SET tecnico_id = ?, estado = 'Asignada' 
                WHERE id = ?
            ");
            $result = $stmt->execute([$tecnicoId, $incidentId]);
        } else {
            $stmt = $this->incidentModel->db->prepare("
                UPDATE incidencias 
                SET tecnico_id = NULL, estado = 'Pendiente' 
                WHERE id = ?
            ");
            $result = $stmt->execute([$incidentId]);
        }
        
        if ($result) {
            $_SESSION['success'] = 'Técnico asignado correctamente';
        } else {
            $_SESSION['errors'] = ['Error al asignar el técnico'];
        }
        
        $this->redirect('/admin/incidencias');
    }
}