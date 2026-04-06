<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - ReparaYa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; }
        .card { background: white; border-radius: 8px; padding: 20px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .btn-success { background: #27ae60; }
        .welcome { font-size: 1.2rem; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🔧 ReparaYa</h1>
            <div class="nav">
                <a href="/dashboard">Inicio</a>
                <a href="/my-incidents">Mis Avisos</a>
                <a href="/new-incident">Nueva Solicitud</a>
                <a href="/profile">Mi Perfil</a>
                <a href="/logout">Cerrar Sesión</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            ¡Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?>!
        </div>
        
        <div class="card">
            <h3>¿Qué deseas hacer?</h3>
            <a href="/new-incident" class="btn btn-success">Solicitar Técnico</a>
            <a href="/my-incidents" class="btn">Ver Mis Avisos</a>
            <a href="/profile" class="btn">Actualizar Perfil</a>
        </div>
    </div>
</body>
</html>
