<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/">ReparaYa - ADMIN</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Hola, <b><?= htmlspecialchars($nombre) ?></b></span>
            <a href="/calendario" class="btn btn-warning btn-sm me-2">Ver calendario</a>
            <a href="/perfil" class="btn btn-outline-light me-2 btn-sm">Perfil</a>
            <a href="/logout" class="btn btn-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 mt-4">
    <h2 class="mb-4">Panel de control</h2>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-dark fw-bold d-flex justify-content-between align-items-center">
            <span>Todas las incidencias</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoAviso">Nuevo aviso manual</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Localizador</th>
                            <th>Cliente</th>
                            <th>Especialidad</th>
                            <th>Fecha Servicio</th>
                            <th>Estado</th>
                            <th>Técnico Asignado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($todas_incidencias)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No hay incidencias registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($todas_incidencias as $inc): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?= htmlspecialchars($inc['localizador']) ?></span><br>
                                        <?php if($inc['tipo_urgencia'] === 'Urgente'): ?>
                                            <span class="badge bg-danger" style="font-size: 0.7em;">Urgente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($inc['nombre_cliente']) ?></td>
                                    <td><?= htmlspecialchars($inc['nombre_especialidad']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($inc['fecha_servicio'])) ?></td>
                                    <td>
                                        <span class="badge" style="background-color: <?= htmlspecialchars($inc['color_calendario']) ?>;">
                                            <?= htmlspecialchars($inc['nombre_estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($inc['nombre_tecnico']): ?>
                                            <?= htmlspecialchars($inc['nombre_tecnico']) ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sin asignar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalGestionar<?= $inc['id'] ?>">Gestionar</button>

                                        <div class="modal fade" id="modalGestionar<?= $inc['id'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content text-start">
                                                    <form action="/actualizar-incidencia" method="POST">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Gestionar aviso: <?= htmlspecialchars($inc['localizador']) ?></h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="incidencia_id" value="<?= $inc['id'] ?>">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Asignar técnico</label>
                                                                <select class="form-select" name="tecnico_id">
                                                                    <option value="">-- Sin asignar --</option>
                                                                    <?php foreach($tecnicos as $tec): ?>
                                                                        <option value="<?= $tec['id'] ?>" <?= ($inc['tecnico_id'] == $tec['id']) ? 'selected' : '' ?>>
                                                                            <?= htmlspecialchars($tec['nombre_completo']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Cambiar estado</label>
                                                                <select class="form-select" name="estado_id" required>
                                                                    <?php foreach($estados as $est): ?>
                                                                        <option value="<?= $est['id'] ?>" <?= ($inc['estado_id'] == $est['id']) ? 'selected' : '' ?>>
                                                                            <?= htmlspecialchars($est['nombre_estado']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-between">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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

<!-- Modal para crear nuevo aviso manual admin -->
<div class="modal fade" id="modalNuevoAviso" tabindex="-1" aria-labelledby="modalNuevoAvisoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevoAvisoLabel">Crear aviso manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="/nueva-incidencia" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cliente solicitante</label>
                        <select class="form-select" name="cliente_id" required>
                            <option value="">-- Selecciona un cliente --</option>
                            <?php foreach($clientes as $cliente): ?>
                                <option value="<?= $cliente['id'] ?>">
                                    <?= htmlspecialchars($cliente['nombre']) ?> (<?= htmlspecialchars($cliente['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Especialidad</label>
                        <select class="form-select" name="especialidad_id" required>
                            <option value="">-- Selecciona una --</option>
                            <?php foreach($especialidades as $esp): ?>
                                <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre_especialidad']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha y hora</label>
                            <input type="datetime-local" class="form-control" name="fecha_servicio" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Urgencia</label>
                            <select class="form-select" name="tipo_urgencia" required>
                                <option value="Estándar">Estándar</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dirección</label>
                        <input type="text" class="form-control" name="direccion" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="2" required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Guardar aviso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>