<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="/~uocx1/">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="./">ReparaYa - ADMIN</a>
        <div class="d-flex align-items-center">
            <a href="panel" class="btn btn-outline-light btn-sm me-2">Volver al panel</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4">Gestión técnicos</h2>

    <?php if (!empty($mensajeExito)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Plantilla de técnicos</span>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoTecnico">Alta técnico</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Especialidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($tecnicos)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No hay técnicos en la plantilla.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($tecnicos as $tec): ?>
                                        <tr>
                                            <td class="text-muted fw-bold">#<?= $tec['id'] ?></td>
                                            <td class="fw-bold"><?= htmlspecialchars($tec['nombre_completo']) ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($tec['nombre_especialidad']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($tec['disponible']): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Baja</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="maestros/cambiar-estado" method="POST" style="display:inline;">
                                                    <input type="hidden" name="tecnico_id" value="<?= $tec['id'] ?>">
                                                    <input type="hidden" name="estado_actual" value="<?= $tec['disponible'] ?>">
                                                    <button type="submit" class="btn btn-sm <?= $tec['disponible'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                                        <?= $tec['disponible'] ? 'Dar de Baja' : 'Dar de Alta' ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoTecnico" tabindex="-1">
    <div class="modal-dialog">
        <form action="maestros/nuevo-tecnico" method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Alta técnico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono de contacto</label>
                    <input type="text" class="form-control" name="telefono" placeholder="Ej: 600 000 000" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Especialidad</label>
                    <select class="form-select" name="especialidad_id" required>
                        <?php foreach($especialidades as $esp): ?>
                            <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre_especialidad']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Crear técnico</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>