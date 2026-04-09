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
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .card { background: white; border-radius: 8px; padding: 25px; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #229954; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-secondary { background: #3498db; }
        .btn-secondary:hover { background: #2980b9; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab { padding: 10px 20px; background: #ecf0f1; cursor: pointer; border-radius: 5px; }
        .tab.active { background: #3498db; color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
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
        <h2><i class="fas fa-user-circle"></i> <?= $pageTitle ?></h2>
        
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
        
        <div class="card">
            <div class="tabs">
                <div class="tab active" onclick="showTab('datos')"><i class="fas fa-user"></i> Datos Personales</div>
                <div class="tab" onclick="showTab('password')"><i class="fas fa-lock"></i> Cambiar Contraseña</div>
            </div>
            
            <!-- Tab Datos Personales -->
            <div id="tab-datos" class="tab-content active">
                <form method="POST" action="/profile">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nombre completo</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Correo electrónico</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Teléfono</label>
                        <input type="tel" name="telefono" value="<?= htmlspecialchars($user['telefono'] ?? '') ?>">
                    </div>
                    <button type="submit"><i class="fas fa-save"></i> Guardar Cambios</button>
                </form>
            </div>
            
            <!-- Tab Cambiar Contraseña -->
            <div id="tab-password" class="tab-content">
                <form method="POST" action="/change-password">
                    <div class="form-group">
                        <label><i class="fas fa-key"></i> Contraseña actual</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Nueva contraseña</label>
                        <input type="password" name="new_password" required>
                        <small>Mínimo 6 caracteres</small>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirmar nueva contraseña</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit"><i class="fas fa-save"></i> Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function showTab(tab) {
            // Ocultar todos los tabs
            document.getElementById('tab-datos').classList.remove('active');
            document.getElementById('tab-password').classList.remove('active');
            
            // Desactivar todos los botones
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            
            // Mostrar el tab seleccionado
            if (tab === 'datos') {
                document.getElementById('tab-datos').classList.add('active');
                document.querySelectorAll('.tab')[0].classList.add('active');
            } else {
                document.getElementById('tab-password').classList.add('active');
                document.querySelectorAll('.tab')[1].classList.add('active');
            }
        }
    </script>
</body>
</html>