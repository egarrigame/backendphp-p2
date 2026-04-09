<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/">ReparaYa</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Hola, <b><?= htmlspecialchars($nombre) ?></b></span>
            <a href="/perfil" class="btn btn-outline-light me-2 btn-sm">Perfil</a>
            <a href="/logout" class="btn btn-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 mt-4">
    
    <?php if (!empty($mensajeExito)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white text-primary fw-bold">
                    Solicitar técnico
                </div>
                <div class="card-body">
                    <form action="/nueva-incidencia" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label">Especialidad</label>
                            <select class="form-select" name="especialidad_id" required>
                                <option value="">-- Selecciona una --</option>
                                <?php foreach($especialidades as $esp): ?>
                                    <option value="<?= $esp['id'] ?>">
                                        <?= htmlspecialchars($esp['nombre_especialidad']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de servicio</label>
                            <select class="form-select" name="tipo_urgencia" required>
                                <option value="Estándar">Estándar (Asignación normal)</option>
                                <option value="Urgente">Urgente (Respuesta rápida)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha y hora</label>
                            <input type="datetime-local" class="form-control" name="fecha_servicio" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección física</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Calle, Número, Piso..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" placeholder="Explica brevemente..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Crear solicitud</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white text-primary fw-bold">
                    Mis Avisos
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Localizador</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Urgencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($mis_incidencias)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No tienes avisos registrados.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($mis_incidencias as $incidencia): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($incidencia['localizador']) ?></td>
                                            <td><?= htmlspecialchars($incidencia['nombre_especialidad']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($incidencia['fecha_servicio'])) ?></td>
                                            <td>
                                                <span class="badge" style="background-color: <?= htmlspecialchars($incidencia['color_calendario']) ?>;">
                                                    <?= htmlspecialchars($incidencia['nombre_estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($incidencia['tipo_urgencia'] === 'Urgente'): ?>
                                                    <span class="badge bg-danger">Urgente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Estándar</span>
                                                <?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>