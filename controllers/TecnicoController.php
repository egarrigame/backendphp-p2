<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Tecnico.php';
require_once __DIR__ . '/../models/Especialidad.php';

class TecnicoController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $tecnicoModel = new Tecnico();
        $especialidadModel = new Especialidad();

        $tecnicos = $tecnicoModel->getAll();
        $especialidades = $especialidadModel->fetchAll("SELECT * FROM especialidades");

        $this->render('admin/tecnicos', [
            'tecnicos' => $tecnicos,
            'especialidades' => $especialidades
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_completo'] ?? '');
        $especialidad_id = (int)($_POST['especialidad_id'] ?? 0);

        if (empty($nombre) || $especialidad_id <= 0) {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/tecnicos');
        }

        $model = new Tecnico();

        $ok = $model->create([
            'nombre_completo' => $nombre,
            'especialidad_id' => $especialidad_id
        ]);

        $_SESSION[$ok ? 'success' : 'error'] =
            $ok ? 'Técnico creado' : 'Error al crear técnico';

        $this->redirect('/tecnicos');
    }

    public function update(): void
    {
        $this->requireAuth();

        $id = (int)$_POST['id'];

        $model = new Tecnico();

        $ok = $model->update($id, [
            'especialidad_id' => $_POST['especialidad_id'],
            'disponible' => $_POST['disponible']
        ]);

        $_SESSION[$ok ? 'success' : 'error'] =
            $ok ? 'Actualizado' : 'Error al actualizar';

        $this->redirect('/tecnicos');
    }

    public function delete(): void
    {
        $this->requireAuth();

        $id = (int)$_POST['id'];

        $model = new Tecnico();

        $ok = $model->delete($id);

        $_SESSION[$ok ? 'success' : 'error'] =
            $ok ? 'Eliminado' : 'Error al eliminar';

        $this->redirect('/tecnicos');
    }

public function agenda(): void
{
    $this->requireAuth();

    if ($_SESSION['user']['rol'] !== 'tecnico') {
        die('Acceso no autorizado');
    }

    $tecnicoModel = new Tecnico();
    $incidenciaModel = new Incidencia();

    // Obtener el técnico asociado al usuario
    $tecnico = $tecnicoModel->fetch(
        "SELECT id FROM tecnicos WHERE usuario_id = :user_id",
        ['user_id' => $_SESSION['user']['id']]
    );

    if (!$tecnico) {
        $this->render('tecnico/agenda', ['incidencias' => []]);
        return;
    }

    $incidencias = $incidenciaModel->fetchAll(
        "SELECT 
            i.*,
            u.nombre AS cliente_nombre,
            e.nombre_especialidad,
            es.nombre_estado
         FROM incidencias i
         JOIN usuarios u ON i.cliente_id = u.id
         JOIN especialidades e ON i.especialidad_id = e.id
         JOIN estados es ON i.estado_id = es.id
         WHERE i.tecnico_id = :tecnico_id
         ORDER BY i.fecha_servicio ASC",
        ['tecnico_id' => $tecnico['id']]
    );

    $this->render('tecnico/agenda', [
        'incidencias' => $incidencias
    ]);
}
}