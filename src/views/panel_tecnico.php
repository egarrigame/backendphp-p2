<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="/~uocx1/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="./">ReparaYa - TÉCNICO</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Hola, <b><?= htmlspecialchars($nombre) ?></b></span>
            <a href="perfil" class="btn btn-outline-light btn-sm me-2">Perfil</a>
            <a href="logout" class="btn btn-danger btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4">Mi calendario de trabajo</h2>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-success fw-bold">
            Servicios asignados
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha y hora</th>
                            <th>Localizador</th>
                            <th>Urgencia</th>
                            <th>Cliente y contacto</th>
                            <th>Dirección</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($mis_trabajos)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <h5 class="fw-normal">No tienes servicios asignados.</h5>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($mis_trabajos as $trabajo): ?>
                                <tr>
                                    <td class="fw-bold text-nowrap"><?= date('d/m/Y H:i', strtotime($trabajo['fecha_servicio'])) ?></td>
                                    <td><?= htmlspecialchars($trabajo['localizador']) ?></td>
                                    <td>
                                        <?php if($trabajo['tipo_urgencia'] === 'Urgente'): ?>
                                            <span class="badge bg-danger">Urgente</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Estándar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($trabajo['nombre_cliente']) ?><br>
                                        <small class="text-muted">📞 <?= htmlspecialchars($trabajo['telefono_cliente'] ?? 'Sin teléfono') ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($trabajo['direccion']) ?></td>
                                    <td><small><?= htmlspecialchars($trabajo['descripcion']) ?></small></td>
                                    <td>
                                        <span class="badge" style="background-color: <?= htmlspecialchars($trabajo['color_calendario']) ?>;">
                                            <?= htmlspecialchars($trabajo['nombre_estado']) ?>
                                        </span>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>