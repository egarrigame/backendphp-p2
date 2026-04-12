<?php

declare(strict_types=1);

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Especialidad.php';

class EspecialidadController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        if ($_SESSION['user']['rol'] !== 'admin') {
            die('Acceso no autorizado');
        }

        $model = new Especialidad();

        $especialidades = $model->fetchAll("SELECT * FROM especialidades");

        $this->render('admin/especialidades', [
            'especialidades' => $especialidades
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_especialidad'] ?? '');

        if (empty($nombre)) {
            $_SESSION['error'] = 'El nombre es obligatorio';
            $this->redirect('/especialidades');
        }

        $model = new Especialidad();

        $ok = $model->execute(
            "INSERT INTO especialidades (nombre_especialidad) VALUES (:nombre)",
            ['nombre' => $nombre]
        );

        if (!$ok) {
            $_SESSION['error'] = 'Error al crear especialidad';
        } else {
            $_SESSION['success'] = 'Especialidad creada correctamente';
        }

        $this->redirect('/especialidades');
    }

    public function update(): void
    {
        $this->requireAuth();

        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre_especialidad'] ?? '');

        if ($id <= 0 || empty($nombre)) {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/especialidades');
        }

        $model = new Especialidad();

        $ok = $model->execute(
            "UPDATE especialidades 
             SET nombre_especialidad = :nombre 
             WHERE id = :id",
            [
                'id' => $id,
                'nombre' => $nombre
            ]
        );

        if (!$ok) {
            $_SESSION['error'] = 'Error al actualizar';
        } else {
            $_SESSION['success'] = 'Especialidad actualizada';
        }

        $this->redirect('/especialidades');
    }

    public function delete(): void
    {
        $this->requireAuth();

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido';
            $this->redirect('/especialidades');
        }

        $model = new Especialidad();

        $ok = $model->execute(
            "DELETE FROM especialidades WHERE id = :id",
            ['id' => $id]
        );

        if (!$ok) {
            $_SESSION['error'] = 'Error al eliminar';
        } else {
            $_SESSION['success'] = 'Especialidad eliminada';
        }

        $this->redirect('/especialidades');
    }
}