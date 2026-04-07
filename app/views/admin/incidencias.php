<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?> - ReparaYa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2c3e50; color: white; }
        .btn { display: inline-block; padding: 8px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; font-size: 12px; cursor: pointer; border: none; }
        .btn-danger { background: #e74c3c; }
        .btn-success { background: #27ae60; }
        .btn-warning { background: #f39c12; }
        .status { padding: 3px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
        .status-pendiente { background: #f39c12; color: white; }
        .status-asignada { background: #3498db; color: white; }
        .status-finalizada { background: #27ae60; color: white; }
        .status-cancelada { background: #95a5a6; color: white; }
        .urgente { border-left: 4px solid #e74c3c; }
        .error, .success { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 5% auto; padding: 20px; width: 500px; border-radius: 8px; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1><i class="fas fa-tools"></i> ReparaYa - Admin</h1>
            <div class="nav">
                <a href="/admin/dashboard"><i class="fas fa-home"></i> Inicio</a>
                <a href="/admin/tecnicos"><i class="fas fa-user-cog"></i> Técnicos</a>
                <a href="/admin/incidencias"><i class="fas fa-clipboard-list"></i> Incidencias</a>
                <a href="/admin/calendar"><i class="fas fa-calendar-alt"></i> Calendario</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <h2><i class="fas fa-clipboard-list"></i> <?= $pageTitle ?></h2>
        
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
        
        <?php if (empty($incidencias)): ?>
            <p><i class="fas fa-info-circle"></i> No hay incidencias registradas.</p>
        <?php else: ?>
            <h3><i class="fas fa-list"></i> Lista de Incidencias</h3>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-barcode"></i> Código</th>
                        <th><i class="fas fa-user"></i> Cliente</th>
                        <th><i class="fas fa-wrench"></i> Servicio</th>
                        <th><i class="fas fa-clock"></i> Tipo</th>
                        <th><i class="fas fa-calendar-day"></i> Fecha</th>
                        <th><i class="fas fa-user-cog"></i> Técnico</th>
                        <th><i class="fas fa-chart-simple"></i> Estado</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidencias as $inc): ?>
                        <tr class="<?= $inc['tipo_urgencia'] == 'Urgente' ? 'urgente' : '' ?>">
                            <td><?= htmlspecialchars($inc['localizador']) ?></td>
                            <td><?= htmlspecialchars($inc['cliente_nombre']) ?></td>
                            <td><?= htmlspecialchars($inc['nombre_especialidad']) ?></td>
                            <td><?= htmlspecialchars($inc['tipo_urgencia']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($inc['fecha_servicio'])) ?></td>
                            <td><?= htmlspecialchars($inc['tecnico_nombre'] ?? 'Sin asignar') ?></td>
                            <td><span class="status status-<?= strtolower($inc['estado']) ?>"><?= htmlspecialchars($inc['estado']) ?></span></td>
                            <td>
                                <button class="btn btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($inc)) ?>)"><i class="fas fa-edit"></i> Editar</button>
                                <button class="btn btn-success" onclick="openAssignModal(<?= $inc['id'] ?>, <?= $inc['tecnico_id'] ?? 'null' ?>)"><i class="fas fa-user-check"></i> Asignar</button>
                                <?php if ($inc['estado'] != 'Cancelada' && $inc['estado'] != 'Finalizada'): ?>
                                    <form method="POST" action="/admin/incidencias/cancel" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $inc['id'] ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Cancelar esta incidencia?')"><i class="fas fa-ban"></i> Cancelar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Modal Editar Incidencia -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-edit"></i> Editar Incidencia</h3>
            <form method="POST" action="/admin/incidencias/update">
                <input type="hidden" name="id" id="edit_id">
                <label><i class="fas fa-wrench"></i> Servicio</label>
                <select name="especialidad_id" id="edit_especialidad" required>
                    <option value="">Selecciona</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre_especialidad']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label><i class="fas fa-clock"></i> Tipo de urgencia</label>
                <select name="tipo_urgencia" id="edit_tipo_urgencia" required>
                    <option value="Estándar">Estándar</option>
                    <option value="Urgente">Urgente (24h)</option>
                </select>
                <label><i class="fas fa-calendar-day"></i> Fecha del servicio</label>
                <input type="datetime-local" name="fecha_servicio" id="edit_fecha_servicio" required>
                <label><i class="fas fa-align-left"></i> Descripción</label>
                <textarea name="descripcion" id="edit_descripcion" required rows="3"></textarea>
                <label><i class="fas fa-map-marker-alt"></i> Dirección</label>
                <input type="text" name="direccion" id="edit_direccion" required>
                <label><i class="fas fa-chart-simple"></i> Estado</label>
                <select name="estado" id="edit_estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Asignada">Asignada</option>
                    <option value="Finalizada">Finalizada</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-danger" onclick="closeEditModal()"><i class="fas fa-times"></i> Cancelar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Asignar Técnico -->
    <div id="assignModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-user-check"></i> Asignar Técnico</h3>
            <form method="POST" action="/admin/incidencias/assign">
                <input type="hidden" name="incident_id" id="assign_incident_id">
                <label><i class="fas fa-user-cog"></i> Técnico</label>
                <select name="tecnico_id" id="assign_tecnico_id" required>
                    <option value="">Selecciona un técnico</option>
                    <?php foreach ($tecnicos as $tec): ?>
                        <option value="<?= $tec['id'] ?>"><?= htmlspecialchars($tec['nombre_completo']) ?> - <?= htmlspecialchars($tec['nombre_especialidad']) ?></option>
                    <?php endforeach; ?>
                    <option value="">Sin técnico (dejar pendiente)</option>
                </select>
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Asignar</button>
                <button type="button" class="btn btn-danger" onclick="closeAssignModal()"><i class="fas fa-times"></i> Cancelar</button>
            </form>
        </div>
    </div>
    
    <script>
        function openEditModal(incidencia) {
            document.getElementById('edit_id').value = incidencia.id;
            document.getElementById('edit_especialidad').value = incidencia.especialidad_id;
            document.getElementById('edit_tipo_urgencia').value = incidencia.tipo_urgencia;
            document.getElementById('edit_fecha_servicio').value = incidencia.fecha_servicio.replace(' ', 'T');
            document.getElementById('edit_descripcion').value = incidencia.descripcion;
            document.getElementById('edit_direccion').value = incidencia.direccion;
            document.getElementById('edit_estado').value = incidencia.estado;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        function openAssignModal(incidentId, tecnicoId) {
            document.getElementById('assign_incident_id').value = incidentId;
            if (tecnicoId) {
                document.getElementById('assign_tecnico_id').value = tecnicoId;
            }
            document.getElementById('assignModal').style.display = 'block';
        }
        
        function closeAssignModal() {
            document.getElementById('assignModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
            if (event.target == document.getElementById('assignModal')) {
                closeAssignModal();
            }
        }
    </script>
</body>
</html>