<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - ReparaYa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .card { background: white; border-radius: 8px; padding: 20px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 10px 10px 0 0; }
        .btn-success { background: #27ae60; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .card-icon { font-size: 48px; text-align: center; margin-bottom: 15px; color: #3498db; }
        .card h3 { text-align: center; margin-bottom: 15px; }
        .card p { text-align: center; color: #7f8c8d; margin-bottom: 20px; }
        .card .btn { display: block; text-align: center; margin: 0; }
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
                <a href="/dashboard"><i class="fas fa-arrow-left"></i> Volver</a>
                <a href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <h2>Panel de Administración</h2>
        <p>Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?> <i class="fas fa-smile-wink"></i></p>
        
        <div class="grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-user-cog"></i></div>
                <h3>Gestión de Técnicos</h3>
                <p>Dar de alta, modificar o eliminar técnicos. Asignar especialidades.</p>
                <a href="/admin/tecnicos" class="btn">Gestionar Técnicos <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="card">
                <div class="card-icon"><i class="fas fa-clipboard-list"></i></div>
                <h3>Gestión de Incidencias</h3>
                <p>Ver, modificar o cancelar cualquier incidencia. Asignar técnicos.</p>
                <a href="/admin/incidencias" class="btn">Gestionar Incidencias <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="card">
                <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
                <h3>Calendario</h3>
                <p>Visualizar servicios por día, semana o mes. Colores por tipo de urgencia.</p>
                <a href="/admin/calendar" class="btn">Ver Calendario <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</body>
</html>