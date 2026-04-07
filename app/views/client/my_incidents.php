<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Avisos - ReparaYa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2c3e50; color: white; }
        .btn { display: inline-block; padding: 8px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .btn-danger { background: #e74c3c; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .btn-success { background: #27ae60; margin-bottom: 20px; display: inline-block; }
        .status { padding: 3px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .status-pendiente { background: #f39c12; color: white; }
        .status-asignada { background: #3498db; color: white; }
        .status-finalizada { background: #27ae60; color: white; }
        .status-cancelada { background: #95a5a6; color: white; }
        .urgente { border-left: 4px solid #e74c3c; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1><i class="fas fa-tools"></i> ReparaYa</h1>
            <div class="nav">
                <a href="/dashboard"><i class="fas fa-home"></i> Inicio</a>
                <a href="/my-incidents"><i class="fas fa-list"></i> Mis Avisos</a>
                <a href="/new-incident"><i class="fas fa-plus-circle"></i> Nueva Solicitud</a>
                <a href="/profile"><i class="fas fa-user-edit"></i> Mi Perfil</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <h2><i class="fas fa-list"></i> Mis Avisos</h2>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <a href="/new-incident" class="btn btn-success"><i class="fas fa-plus-circle"></i> Nueva Solicitud</a>
        
        <?php if (empty($incidents)): ?>
            <p><i class="fas fa-info-circle"></i> No tienes avisos registrados.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-barcode"></i> Código</th>
                        <th><i class="fas fa-wrench"></i> Servicio</th>
                        <th><i class="fas fa-clock"></i> Tipo</th>
                        <th><i class="fas fa-calendar-day"></i> Fecha</th>
                        <th><i class="fas fa-chart-simple"></i> Estado</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidents as $inc): ?>
                        <tr class="<?= $inc['tipo_urgencia'] == 'Urgente' ? 'urgente' : '' ?>">
                            <td><?= htmlspecialchars($inc['localizador']) ?></td>
                            <td><?= htmlspecialchars($inc['nombre_especialidad']) ?></td>
                            <td><?= htmlspecialchars($inc['tipo_urgencia']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($inc['fecha_servicio'])) ?></td>
                            <td>
                                <span class="status status-<?= strtolower($inc['estado']) ?>">
                                    <?= htmlspecialchars($inc['estado']) ?>
                                </span>
                             </td>
                            <td>
                                <?php if ($inc['estado'] == 'Pendiente'): ?>
                                    <form method="POST" action="/cancel-incident" style="display:inline;">
                                        <input type="hidden" name="incident_id" value="<?= $inc['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('¿Estás seguro de cancelar este aviso?')"><i class="fas fa-ban"></i> Cancelar</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:#95a5a6"><i class="fas fa-lock"></i> No disponible</span>
                                <?php endif; ?>
                             </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>