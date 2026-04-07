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
        .form-container { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .error, .success { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
        .disponible { color: green; font-weight: bold; }
        .no-disponible { color: red; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 400px; border-radius: 8px; }
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
        <h2><i class="fas fa-user-cog"></i> <?= $pageTitle ?></h2>
        
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
        
        <!-- Formulario para crear técnico -->
        <div class="form-container">
            <h3><i class="fas fa-plus-circle"></i> Nuevo Técnico</h3>
            <form method="POST" action="/admin/tecnicos/create">
                <label><i class="fas fa-user"></i> Nombre completo *</label>
                <input type="text" name="nombre_completo" required>
                
                <label><i class="fas fa-wrench"></i> Especialidad *</label>
                <select name="especialidad_id" required>
                    <option value="">Selecciona</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre_especialidad']) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label style="display: inline-block;">
                    <input type="checkbox" name="disponible" checked> <i class="fas fa-check-circle"></i> Disponible
                </label>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Crear Técnico</button>
            </form>
        </div>
        
        <!-- Tabla de técnicos -->
        <?php if (empty($tecnicos)): ?>
            <p><i class="fas fa-info-circle"></i> No hay técnicos registrados.</p>
        <?php else: ?>
            <h3><i class="fas fa-list"></i> Lista de Técnicos</h3>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-wrench"></i> Especialidad</th>
                        <th><i class="fas fa-check-circle"></i> Disponible</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tecnicos as $tec): ?>
                        <tr>
                            <td><?= $tec['id'] ?></td>
                            <td><?= htmlspecialchars($tec['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($tec['nombre_especialidad']) ?></td>
                            <td class="<?= $tec['disponible'] ? 'disponible' : 'no-disponible' ?>">
                                <?= $tec['disponible'] ? '<i class="fas fa-check-circle"></i> Disponible' : '<i class="fas fa-times-circle"></i> No disponible' ?>
                             </td>
                            <td>
                                <button class="btn btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($tec)) ?>)"><i class="fas fa-edit"></i> Editar</button>
                                <form method="POST" action="/admin/tecnicos/delete" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $tec['id'] ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar este técnico?')"><i class="fas fa-trash-alt"></i> Eliminar</button>
                                </form>
                             </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <!-- Modal Editar -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-edit"></i> Editar Técnico</h3>
            <form method="POST" action="/admin/tecnicos/update">
                <input type="hidden" name="id" id="edit_id">
                <label><i class="fas fa-user"></i> Nombre completo</label>
                <input type="text" name="nombre_completo" id="edit_nombre" required>
                <label><i class="fas fa-wrench"></i> Especialidad</label>
                <select name="especialidad_id" id="edit_especialidad" required>
                    <option value="">Selecciona</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre_especialidad']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>
                    <input type="checkbox" name="disponible" id="edit_disponible"> <i class="fas fa-check-circle"></i> Disponible
                </label>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-danger" onclick="closeModal()"><i class="fas fa-times"></i> Cancelar</button>
            </form>
        </div>
    </div>
    
    <script>
        function openEditModal(tecnico) {
            document.getElementById('edit_id').value = tecnico.id;
            document.getElementById('edit_nombre').value = tecnico.nombre_completo;
            document.getElementById('edit_especialidad').value = tecnico.especialidad_id;
            document.getElementById('edit_disponible').checked = tecnico.disponible == 1;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>