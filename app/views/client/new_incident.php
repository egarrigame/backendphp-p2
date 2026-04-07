<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Solicitud - ReparaYa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; }
        textarea { resize: vertical; min-height: 80px; }
        button { margin-top: 20px; padding: 12px 30px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #229954; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .required { color: red; }
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
        <h2><i class="fas fa-plus-circle"></i> Nueva Solicitud</h2>
        
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
        
        <div class="form-container">
            <form method="POST" action="/new-incident">
                <label><i class="fas fa-wrench"></i> Tipo de servicio <span class="required">*</span></label>
                <select name="especialidad_id" required>
                    <option value="">Selecciona un servicio</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= $esp['id'] ?>"><?= htmlspecialchars($esp['nombre_especialidad']) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label><i class="fas fa-clock"></i> Tipo de urgencia <span class="required">*</span></label>
                <select name="tipo_urgencia" required>
                    <option value="">Selecciona</option>
                    <option value="Estándar">Estándar</option>
                    <option value="Urgente">Urgente (24h)</option>
                </select>
                
                <label><i class="fas fa-calendar-day"></i> Fecha del servicio <span class="required">*</span></label>
                <input type="datetime-local" name="fecha_servicio" required>
                
                <label><i class="fas fa-phone"></i> Teléfono de contacto <span class="required">*</span></label>
                <input type="tel" name="telefono_contacto" required>
                
                <label><i class="fas fa-map-marker-alt"></i> Dirección <span class="required">*</span></label>
                <input type="text" name="direccion" required>
                
                <label><i class="fas fa-align-left"></i> Descripción de la avería <span class="required">*</span></label>
                <textarea name="descripcion" required></textarea>
                
                <button type="submit"><i class="fas fa-paper-plane"></i> Solicitar Técnico</button>
            </form>
        </div>
    </div>
</body>
</html>